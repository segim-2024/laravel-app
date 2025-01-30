<?php

namespace App\Services\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Exceptions\MemberCashRepositoryFactoryException;
use App\Exceptions\MemberCashTransactionRepositoryFactoryException;
use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;

interface MemberCashServiceInterface
{
    /**
     * @param MemberInterface $member
     * @return CashInterface
     * @throws MemberCashRepositoryFactoryException
     */
    public function create(MemberInterface $member): CashInterface;

    /**
     * @param MemberCashDTO $DTO
     * @return CashInterface
     * @throws MemberCashRepositoryFactoryException
     * @throws MemberCashTransactionRepositoryFactoryException
     */
    public function charge(MemberCashDTO $DTO): CashInterface;

    /**
     * @param MemberCashDTO $DTO
     * @return CashInterface
     * @throws MemberCashRepositoryFactoryException
     * @throws MemberCashTransactionRepositoryFactoryException
     * @throws MemberCashNotEnoughToSpendException
     */
    public function spend(MemberCashDTO $DTO): CashInterface;

    /**
     * @param CashInterface $cash
     * @param int $amount
     * @return bool
     */
    public function check(CashInterface $cash, int $amount): bool;
}
