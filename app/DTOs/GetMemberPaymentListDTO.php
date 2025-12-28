<?php

namespace App\DTOs;

use App\Models\Interfaces\MemberInterface;
use Illuminate\Support\Carbon;

class GetMemberPaymentListDTO
{
    public function __construct(
        public readonly MemberInterface $member,
        public readonly ?Carbon $start,
        public readonly ?Carbon $end,
        public readonly ?string $keyword,
    ) {}
}
