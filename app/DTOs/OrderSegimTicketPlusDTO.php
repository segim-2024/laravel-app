<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class OrderSegimTicketPlusDTO
{
    public function __construct(
        public Collection $ctIds,
    ) {}
}
