<?php

namespace App\Services\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Models\Member;
use App\Models\MemberCash;

interface MemberCashServiceInterface
{
    /**
     * @param Member $member
     * @return MemberCash
     */
    public function create(Member $member):MemberCash;

    /**
     * @param MemberCashDTO $DTO
     * @return MemberCash
     */
    public function charge(MemberCashDTO $DTO):MemberCash;

    /**
     * @param MemberCashDTO $DTO
     * @return MemberCash
     * @throws MemberCashNotEnoughToSpendException
     */
    public function spend(MemberCashDTO $DTO):MemberCash;

    /**
     * @param Member $member
     * @param int $amount
     * @return bool
     */
    public function check(Member $member, int $amount): bool;
}
