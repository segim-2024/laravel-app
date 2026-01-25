<?php

namespace App\Repositories\Factories;

use App\Models\Interfaces\MemberInterface;
use App\Repositories\Eloquent\MileageHistoryRepository;
use App\Repositories\Interfaces\MileageHistoryRepositoryInterface;

class MileageHistoryRepositoryFactory
{
    public function create(MemberInterface $member): MileageHistoryRepositoryInterface
    {
        // 추후 고래영어 마일리지 추가 시 분기 처리
        // return $member->isWhale()
        //     ? app(WhaleMileageHistoryRepository::class)
        //     : app(MileageHistoryRepository::class);

        return app(MileageHistoryRepository::class);
    }
}