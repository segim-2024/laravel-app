<?php
namespace App\Repositories\Interfaces;

use App\Models\Member;
use Illuminate\Support\Collection;

interface MemberPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;
}
