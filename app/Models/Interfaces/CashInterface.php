<?php

namespace App\Models\Interfaces;

interface CashInterface {
    /**
     * @return string
     */
    public function getMemberId(): string;

    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @param int $amount
     * @return void
     */
    public function setAmount(int $amount): void;
}
