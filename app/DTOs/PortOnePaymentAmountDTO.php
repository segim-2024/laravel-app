<?php

namespace App\DTOs;

class PortOnePaymentAmountDTO
{
    public function __construct(
        public readonly int $total,
        public readonly int $taxFree,
        public readonly int $vat,
        public readonly int $supply,
        public readonly int $discount,
        public readonly int $paid,
        public readonly int $cancelled,
        public readonly int $cancelledTaxFree,
    ) {}

    /**
     * @param object $amount
     * @return self
     */
    public static function fromResponseObject(object $amount):self
    {
        return new self(
            total: $amount->total,
            taxFree: $amount->taxFree,
            vat: $amount->vat ?? 0,
            supply: $amount->supply ?? 0,
            discount: $amount->discount,
            paid: $amount->paid,
            cancelled: $amount->cancelled ?? 0,
            cancelledTaxFree: $amount->cancelledTaxFree ?? 0,
        );
    }
}
