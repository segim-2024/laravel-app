<?php

namespace App\Services;

use App\DTOs\AccessTokenDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Services\Interfaces\MemberServiceInterface;
use Illuminate\Support\Facades\Config;

class MemberService implements MemberServiceInterface {
    public function __construct(
        protected MemberRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $id, bool $isWhale = false): Member|WhaleMember|null
    {
        return match ($isWhale) {
            true => $this->repository->findFromWhale($id),
            default => $this->repository->find($id),
        };
    }

    /**
     * @inheritDoc
     */
    public function updateTossCustomerKey(Member $member): Member
    {
        return $this->repository->updateTossCustomerKey($member);
    }

    /**
     * @inheritDoc
     */
    public function createToken(MemberInterface $member): AccessTokenDTO
    {
        return new AccessTokenDTO(
            $member->createToken('default')->plainTextToken,
            now()->addMinutes(Config::get('sanctum.expiration'))->toDateTimeString(),
        );
    }
}
