<?php

namespace App\Services;

use App\DTOs\LibraryMemberLookupDTO;
use App\Exceptions\LibraryMemberPasswordMismatchException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
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
            : $this->findPamus($DTO->account);

        // 탈퇴/차단 회원은 존재 자체를 노출하지 않는다
        if (! $member || ! $member->isActive()) {
            return null;
        }

        if (! GnuboardPasswordVerifier::verify($DTO->password, (string) $member->mb_password)) {
            throw new LibraryMemberPasswordMismatchException;
        }

        return $member;
    }

    /**
     * 파머스 DB 에서 회원을 찾는다. 고래영어 회원의 사본은 없는 회원으로 취급한다.
     */
    private function findPamus(string $account): ?Member
    {
        $member = $this->repository->find($account);

        return $member?->isWhaleCopy() ? null : $member;
    }
}
