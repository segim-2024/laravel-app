<?php
namespace App\Repositories\Interfaces;

use App\Models\Member;
use App\Models\WhaleMember;

interface MemberRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $id
     * @return Member|null
     */
    public function find(string $id): ?Member;

    /**
     * @param string $id
     * @return WhaleMember|null
     */
    public function findFromWhale(string $id): ?WhaleMember;

    /**
     * @param Member $member
     * @return Member
     */
    public function updateTossCustomerKey(Member $member): Member;
}
