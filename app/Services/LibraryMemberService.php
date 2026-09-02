<?php

namespace App\Services;

use App\DTOs\LibraryMemberLookupDTO;
use App\Exceptions\LibraryMemberPasswordMismatchException;
use App\Models\Interfaces\MemberInterface;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Services\Interfaces\LibraryMemberServiceInterface;
use App\Support\GnuboardPasswordVerifier;

class LibraryMemberService implements LibraryMemberServiceInterface
{
    public function __construct(
        protected MemberRepositoryInterface $repository
    ) {}

    /**
     * {@inheritDoc}
     */
    public function find(LibraryMemberLookupDTO $DTO): ?MemberInterface
    {
        $member = $DTO->target->isWhale()
            ? $this->repository->findFromWhale($DTO->account)
            : $this->repository->find($DTO->account);

        // 탈퇴/차단 회원은 존재 자체를 노출하지 않는다
        if (! $member || ! $member->isActive()) {
            return null;
        }

        if (! GnuboardPasswordVerifier::verify($DTO->password, (string) $member->mb_password)) {
            throw new LibraryMemberPasswordMismatchException;
        }

        return $member;
    }
}
