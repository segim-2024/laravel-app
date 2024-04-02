<?php

namespace App\Services\Interfaces;

use App\Models\Member;

interface MemberServiceInterface
{
    /**
     * @param Member $member
     * @return Member
     */
    public function updateTossCustomerKey(Member $member): Member;
}
