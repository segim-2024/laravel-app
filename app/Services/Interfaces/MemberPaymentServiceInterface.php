<?php

namespace App\Services\Interfaces;

use App\Models\Member;
use Illuminate\Support\Collection;

interface MemberPaymentServiceInterface
{
    /**
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;
}
