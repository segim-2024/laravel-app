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
     * 탈퇴하거나 차단되지 않은 회원인지 여부
     */
    public function isActive(): bool;

    /**
     * 마일리지 사용 권한이 있는지 확인
     */
    public function hasMileageAccess(): bool;
}
