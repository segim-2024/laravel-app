<?php

namespace App\DTOs;

use App\Models\Member;

class CreateMemberCardDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly string $name,
        public readonly string $number,
        public readonly string $key,
    ) {}
}
