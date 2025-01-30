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
}
