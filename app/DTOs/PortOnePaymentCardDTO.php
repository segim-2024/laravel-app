<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Log;

class PortOnePaymentCardDTO
{
    public function __construct(
        public readonly ?string $publisher,
        public readonly ?string $issuer,
        public readonly ?string $brand,
        public readonly ?string $type,
        public readonly ?string $ownerType,
        public readonly ?string $bin,
        public readonly ?string $name,
        public readonly ?string $number,
    ) {}

    public static function fromResponseObject(object $card):self
    {
        return new self(
            publisher: $card->publisher ?? null,
            issuer: $card->issuer ?? null,
            brand: $card->brand ?? null,
            type: $card->type ?? null,
            ownerType: $card->ownerType ?? null,
            bin: $card->bin ?? null,
            name: $card->name ?? null,
            number: $card->number ?? null,
        );
    }
}
