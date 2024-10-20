<?php

namespace App\DTOs;

class PortOnePaymentFailureDTO
{
    public function __construct(
        public readonly string $reason,
        public readonly string $pgCode,
        public readonly string $pgMessage
    ) {}
}
