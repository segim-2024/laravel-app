<?php

namespace App\DTOs;

use App\Enums\SegimTicketMinusTypeEnum;
use Illuminate\Support\Collection;

class OrderSegimTicketMinusDTO
{
    public function __construct(
        public SegimTicketMinusTypeEnum $type,
        public readonly Collection      $ids,
    ) {}
}
