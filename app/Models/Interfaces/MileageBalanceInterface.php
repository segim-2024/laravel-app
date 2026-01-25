<?php

namespace App\Models\Interfaces;

interface MileageBalanceInterface
{
    public function getMbNo(): int;

    public function getMbId(): string;

    public function getTotalAccrued(): int;

    public function getTotalConverted(): int;

    public function getTotalUsed(): int;

    public function getBalance(): int;

    /**
     * 전환 가능 마일리지 계산 (잔액 - 전환 한도)
     */
    public function getConvertibleAmount(int $convertThreshold): int;
}
