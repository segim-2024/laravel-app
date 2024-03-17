<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MemberCash;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;
use App\Services\Interfaces\MemberCashServiceInterface;

class MemberCashService implements MemberCashServiceInterface {
    public function __construct(
        protected MemberCashRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function create(Member $member): MemberCash
    {
        return $this->repository->save($member);
    }
}
