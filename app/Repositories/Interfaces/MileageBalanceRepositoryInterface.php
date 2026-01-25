<?php

namespace App\Repositories\Interfaces;

use App\Models\Interfaces\MileageBalanceInterface;

interface MileageBalanceRepositoryInterface
{
    /**
     * 회원의 마일리지 잔액 정보 조회
     */
    public function findByMbNo(int $mbNo): ?MileageBalanceInterface;

    /**
     * 회원 ID로 마일리지 잔액 정보 조회
     */
    public function findByMbId(string $mbId): ?MileageBalanceInterface;
}
