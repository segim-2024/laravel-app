<?php

namespace App\Repositories\Factories;

use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MileageBalanceRepository;
use App\Repositories\Interfaces\MileageBalanceRepositoryInterface;

class MileageBalanceRepositoryFactory
{
    public function create(MemberInterface $member): MileageBalanceRepositoryInterface
    {
        return match (true) {
            // TODO: 고래영어 테이블 생성 후 추가
            // $member instanceof WhaleMember => app(WhaleMileageBalanceRepository::class),
            $member instanceof Member => app(MileageBalanceRepository::class),
            default => app(MileageBalanceRepository::class),
        };
    }
}
