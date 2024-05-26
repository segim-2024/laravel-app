<?php

namespace App\DTOs;

class TossPaymentCancelDTO
{
    public function __construct(
        public readonly string  $transactionKey,
        public readonly string  $cancelReason,
        public readonly int     $taxExemptionAmount,
        public readonly string  $canceledAt,
        public readonly int     $easyPayDiscountAmount,
        public readonly ?string $receiptKey,
        public readonly int     $cancelAmount,
        public readonly int     $taxFreeAmount,
        public readonly int     $refundableAmount,
        public readonly string  $cancelStatus,
        public readonly ?string $cancelRequestId
    ) {}

    public static function createFromPaymentResponse(object $cancel): self
    {
        return new self(
            transactionKey: $cancel->transactionKey,
            cancelReason: $cancel->cancelReason,
            taxExemptionAmount: $cancel->taxExemptionAmount,
            canceledAt: $cancel->canceledAt,
            easyPayDiscountAmount: $cancel->easyPayDiscountAmount,
            receiptKey: $cancel->receiptKey ?? null,
            cancelAmount: $cancel->cancelAmount,
            taxFreeAmount: $cancel->taxFreeAmount,
            refundableAmount: $cancel->refundableAmount,
            cancelStatus: $cancel->cancelStatus,
            cancelRequestId: $cancel->cancelRequestId ?? null,
        );
    }

    public static function createFromPaymentWebHook(array $cancel):self
    {
        return new self(
            transactionKey: $cancel['transactionKey'],
            cancelReason: $cancel['cancelReason'],
            taxExemptionAmount: $cancel['taxExemptionAmount'],
            canceledAt: $cancel['canceledAt'],
            easyPayDiscountAmount: $cancel['easyPayDiscountAmount'],
            receiptKey: $cancel['receiptKey'] ?? null,
            cancelAmount: $cancel['cancelAmount'],
            taxFreeAmount: $cancel['taxFreeAmount'],
            refundableAmount: $cancel['refundableAmount'],
            cancelStatus: $cancel['cancelStatus'],
            cancelRequestId: $cancel['cancelRequestId'] ?? null,
        );
    }
}
