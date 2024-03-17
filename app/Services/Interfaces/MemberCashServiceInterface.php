<?php

namespace App\Services\Interfaces;

use App\Models\Member;
use App\Models\MemberCash;

interface MemberCashServiceInterface
{
    /**
     * @param Member $member
     * @return MemberCash
     */
    public function create(Member $member):MemberCash;
}
