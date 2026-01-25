<?php

namespace App\Models\Interfaces;

interface MemberInterface
{
    /**
     * @return string
     */
    public function getMemberId(): string;

    /**
     * @return ?CashInterface
     */
    public function getCash(): ?CashInterface;

    public function isWhale(): bool;

    /**
     * 마일리지 사용 권한이 있는지 확인
     */
    public function hasMileageAccess(): bool;
}
