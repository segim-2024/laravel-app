<?php

namespace App\DTOs;

use App\Models\MemberPayment;

class UpdateLibraryPaymentApiLogDTO
{
    public function __construct(
        public readonly string $paymentId,
        public readonly bool $isSuccess,
        public readonly ?string $message,
        public readonly string $httpStatusCode,
        public readonly string $data,
    ) {}

    public static function createFromPaymentAndResponseDTO(MemberPayment $payment, LibraryPaymentDoneApiResponseDTO $DTO):self
    {
        return new self(
            paymentId: $payment->payment_id,
            isSuccess: $DTO->isSuccess,
            message: $DTO->message,
            httpStatusCode: (string)$DTO->httpStatusCode,
            data: $DTO->data,
        );
    }

    public function toModelAttribute(): array
    {
        return [
            'is_success' => $this->isSuccess,
            'message' => $this->message,
            'status_code' => $this->httpStatusCode,
            'data' => $this->data,
        ];
    }
}
