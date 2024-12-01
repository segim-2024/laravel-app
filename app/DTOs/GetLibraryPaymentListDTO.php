<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

class GetLibraryPaymentListDTO
{
    public function __construct(
        public readonly string $memberId,
        public readonly ?Carbon $start,
        public readonly ?Carbon $end,
        public readonly ?string $keyword,
    ) {}
}
