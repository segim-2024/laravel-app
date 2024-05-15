<?php
namespace App\Repositories\Interfaces;

use App\Models\Member;

interface MemberRepositoryInterface extends BaseRepositoryInterface
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
