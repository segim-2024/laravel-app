<?php

namespace App\DTOs;

class PortOnePaymentInstallmentDTO
{
    public function __construct(
        public readonly int $month,
        public readonly bool $isInterestFree,
    )
    {
    }

    public static function fromResponseObject(object $installment):self
    {
        return new self(
            month: $installment->month,
            isInterestFree: $installment->isInterestFree,
        );
    }
}
