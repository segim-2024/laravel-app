<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use Illuminate\Support\Collection;

class MemberPaymentService implements MemberPaymentServiceInterface {
    public function __construct(
        protected MemberPaymentRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return $this->repository->getList($member);
    }
}
