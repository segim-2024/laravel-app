<?php

namespace App\DTOs;

use App\Enums\SegimTicketTypeEnum;

class SegimTicketApiDTO
{
    public function __construct(
        public string $mbNo,
        public int $qty,
        public SegimTicketTypeEnum $ticketType,
        public string $description
    ) {}

    public function toPayload(): array
    {
        return [
            'mb_no' => $this->mbNo,
            'amount' => $this->qty,
            'ticket_type' => $this->ticketType->value,
            'description' => $this->description,
        ];
    }
}
