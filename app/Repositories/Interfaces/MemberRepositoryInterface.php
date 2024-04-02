<?php
namespace App\Repositories\Interfaces;

use App\Models\Member;

interface MemberRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Member $member
     * @return Member
     */
    public function updateTossCustomerKey(Member $member): Member;
}
