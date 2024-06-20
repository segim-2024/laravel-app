<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberCardDTO;
use App\Exceptions\PortOneGetBillingKeyException;
use App\Models\Member;
use App\Models\MemberCard;
use Illuminate\Support\Collection;

interface MemberCardServiceInterface
{
    /**
     * @param Member $member
     * @param string|int $id
     * @return MemberCard|null
     */
    public function find(Member $member, string|int $id): ?MemberCard;

    /**
     * 카드 리스트
     *
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;

    /**
     * 카드 생성
     *
     * @param CreateMemberCardDTO $DTO
     * @return MemberCard
     * @throws PortOneGetBillingKeyException
 */
    public function save(CreateMemberCardDTO $DTO):MemberCard;

    /**
     * @param MemberCard $card
     * @return void
     */
    public function delete(MemberCard $card): void;
}
