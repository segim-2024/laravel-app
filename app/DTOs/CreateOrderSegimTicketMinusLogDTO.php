<?php

namespace App\DTOs;

use App\Enums\SegimTicketTypeEnum;
use App\Models\Cart;
use App\Models\ReturnItem;

class CreateOrderSegimTicketMinusLogDTO
{
    public function __construct(
        public string $memberId,
        public string $itemId,
        public Cart|ReturnItem $orderProduct,
        public SegimTicketTypeEnum $ticketType,
        public int $qty,
        public string $api
    ) {}
}
