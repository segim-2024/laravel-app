<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Services\Interfaces\MemberServiceInterface;

class MemberService implements MemberServiceInterface {
    public function __construct(
        protected MemberRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function updateTossCustomerKey(Member $member): Member
    {
        return $this->repository->updateTossCustomerKey($member);
    }
}
