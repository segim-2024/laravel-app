<?php

namespace App\DTOs;

use App\Models\Member;
use Illuminate\Support\Carbon;

class GetMemberOrderListDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly ?Carbon $start,
        public readonly ?Carbon $end,
        public readonly ?string $keyword,
    ) {}
}
