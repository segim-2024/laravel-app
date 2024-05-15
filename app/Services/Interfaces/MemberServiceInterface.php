<?php

namespace App\Services\Interfaces;

use App\Models\Member;

interface MemberServiceInterface
{
    /**
     * @param string $id
     * @return Member|null
     */
    public function find(string $id): ?Member;

    /**
     * @param Member $member
     * @return Member
     */
    public function updateTossCustomerKey(Member $member): Member;
}
