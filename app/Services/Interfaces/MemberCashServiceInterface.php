<?php

namespace App\Services\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;

interface MemberCashServiceInterface
{
    /**
     * @param MemberInterface $member
     * @return CashInterface
     */
    public function create(MemberInterface $member): CashInterface;

    /**
     * @param MemberCashDTO $DTO
     * @return CashInterface
     */
    public function charge(MemberCashDTO $DTO): CashInterface;

    /**
     * @param MemberCashDTO $DTO
     * @return CashInterface
     * @throws MemberCashNotEnoughToSpendException
     */
    public function spend(MemberCashDTO $DTO): CashInterface;

    /**
     * @param MemberInterface $member
     * @param int $amount
     * @return bool
     */
    public function check(MemberInterface $member, int $amount): bool;
}
