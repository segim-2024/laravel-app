<?php

namespace App\Services\Interfaces;

use App\DTOs\AccessTokenDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;

interface MemberServiceInterface
{
    /**
     * @param string $id
     * @param bool $isWhale
     * @return Member|WhaleMember|null
     */
    public function find(string $id, bool $isWhale = false): Member|WhaleMember|null;

    /**
     * @param Member $member
     * @return Member
     */
    public function updateTossCustomerKey(Member $member): Member;

    /**
     * @param MemberInterface $member
     * @return AccessTokenDTO
     */
    public function createToken(MemberInterface $member): AccessTokenDTO;
}
