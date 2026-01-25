<?php

namespace App\Repositories\Factories;

use App\Models\Interfaces\MemberInterface;
use App\Repositories\Eloquent\MileagePolicyRepository;
use App\Repositories\Interfaces\MileagePolicyRepositoryInterface;

class MileagePolicyRepositoryFactory
{
    public function create(MemberInterface $member): MileagePolicyRepositoryInterface
    {
        // 추후 고래영어 마일리지 정책 추가 시 분기 처리
        // return $member->isWhale()
        //     ? app(WhaleMileagePolicyRepository::class)
        //     : app(MileagePolicyRepository::class);

        return app(MileagePolicyRepository::class);
    }
}