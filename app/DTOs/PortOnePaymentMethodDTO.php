<?php

namespace App\DTOs;

use App\Enums\PortOnePaymentMethodEnum;

class PortOnePaymentMethodDTO
{
    public function __construct(
        public readonly PortOnePaymentMethodEnum      $type,
        public readonly ?PortOnePaymentCardDTO        $card,
        public readonly ?string                       $approvalNumber,
        public readonly ?PortOnePaymentInstallmentDTO $installment,
        public readonly ?bool                         $pointUsed
    )
    {
    }

    public static function fromResponseObject(object $method):self
    {
        return new self(
            type: PortOnePaymentMethodEnum::fromString($method->type),
            card: isset($method->card)
                ? PortOnePaymentCardDTO::fromResponseObject($method->card)
                : null,
            approvalNumber: $method->approvalNumber ?? null,
            installment: isset($method->card)
                ? PortOnePaymentInstallmentDTO::fromResponseObject($method->installment)
                : null,
            pointUsed: $method->pointUsed ?? null,
        );
    }
}
