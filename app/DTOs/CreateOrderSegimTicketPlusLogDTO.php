<?php

namespace App\DTOs;

use App\Enums\SegimTicketTypeEnum;

class CreateOrderSegimTicketPlusLogDTO
{
    public function __construct(
        public string $ctId,
        public SegimTicketTypeEnum $ticketType,
        public string $api
    ) {}
}
