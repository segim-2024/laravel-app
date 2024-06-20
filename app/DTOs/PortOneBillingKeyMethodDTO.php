<?php

namespace App\DTOs;

use App\Enums\PortOneBillingKeyMethodEnum;

class PortOneBillingKeyMethodDTO
{
    public function __construct(
        public readonly PortOneBillingKeyMethodEnum $type,
        public readonly ?PortOnePaymentCardDTO $card
    ) {}

    public static function fromResponseObject(object $method):self
    {
        return new self(
            type: PortOneBillingKeyMethodEnum::fromString($method->type),
            card: isset($method->card)
                ? PortOnePaymentCardDTO::fromResponseObject($method->card)
                : null,
        );
    }
}
