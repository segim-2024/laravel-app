<?php
namespace App\Repositories\Interfaces;

use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;

interface MemberCashRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberInterface $member
     * @return CashInterface
     */
    public function save(MemberInterface $member): CashInterface;

    /**
     * @param MemberInterface $member
     * @return ?CashInterface
     */
    public function lock(MemberInterface $member): ?CashInterface;

    /**
     * @param CashInterface $cash
     * @param int $amount
     * @return CashInterface
     */
    public function charge(CashInterface $cash, int $amount): CashInterface;

    /**
     * @param CashInterface $cash
     * @param int $amount
     * @return CashInterface
     */
    public function spend(CashInterface $cash, int $amount): CashInterface;
}
