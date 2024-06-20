<?php

namespace App\DTOs;

use App\Models\Member;

class MemberCardDTO
{
    public function __construct(
        public Member $member,
        public string $name,
        public string $number,
        public string $key,
    ) {}

    public static function create(CreateMemberCardDTO $DTO, PortOneGetBillingKeyResponseDTO $billingKeyDTO): MemberCardDTO
    {
        return new MemberCardDTO(
            member: $DTO->member,
            name: $billingKeyDTO->methods->card->brand.$billingKeyDTO->methods->card->name,
            number: $billingKeyDTO->methods->card->number,
            key: $DTO->key,
        );
    }
}
