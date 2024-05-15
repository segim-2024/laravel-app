<?php
namespace App\Repositories\Interfaces;

use App\Models\Member;
use App\Models\MemberCash;

interface MemberCashRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Member $member
     * @return MemberCash
     */
    public function save(Member $member):MemberCash;

    /**
     * @param Member $member
     * @return MemberCash
     */
    public function lock(Member $member):MemberCash;

    /**
     * @param MemberCash $cash
     * @param int $amount
     * @return MemberCash
     */
    public function charge(MemberCash $cash, int $amount):MemberCash;

    /**
     * @param MemberCash $cash
     * @param int $amount
     * @return mixed
     */
    public function spend(MemberCash $cash, int $amount):MemberCash;

    /**
     * @param Member $member
     * @param int $amount
     * @return bool
     */
    public function canSpendCheck(Member $member, int $amount): bool;
}
