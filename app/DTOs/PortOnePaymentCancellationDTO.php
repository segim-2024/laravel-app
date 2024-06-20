<?php

namespace App\DTOs;

class PortOnePaymentCancellationDTO
{
    public function __construct(
        public readonly string $status,
        public readonly string $id,
        public readonly ?string $pgCancellationId,
        public readonly int $totalAmount,
        public readonly int $taxFreeAmount,
        public readonly int $vatAmount,
        public readonly string $reason,
        public readonly ?string $cancelledAt,
    ) {}

    /**
     * @param object $cancelled
     * @return self
     */
    public static function fromResponseObject(object $cancelled):self
    {
        return new self(
            status: $cancelled->status,
            id: $cancelled->id,
            pgCancellationId: $cancelled->pgCancellationId ?? "",
            totalAmount: $cancelled->totalAmount,
            taxFreeAmount: $cancelled->taxFreeAmount,
            vatAmount: $cancelled->vatAmount,
            reason: $cancelled->reason ?? "",
            cancelledAt: $cancelled->cancelledAt ?? null,
        );
    }
}
