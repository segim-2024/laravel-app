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
}
