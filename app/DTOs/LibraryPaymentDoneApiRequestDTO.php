<?php

namespace App\DTOs;

use App\Models\MemberPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class LibraryPaymentDoneApiRequestDTO
{
    public function __construct(
        public string $memberId,
        public int $ticketQty,
        public Carbon $paidAt,
        public string $paymentId
    ) {}

    public static function createFromPaymentModel(MemberPayment $payment): self
    {
        return new self(
            memberId: $payment->member_id,
            ticketQty: $payment->productable->ticket_provide_qty,
            paidAt: Carbon::create($payment->paid_at),
            paymentId: $payment->payment_id
        );
    }

    public function toPayload(): array
    {
        return [
            'campus_id' => $this->memberId,
            'study_persons' => $this->ticketQty,
            'payment_date' => $this->paidAt->toDateString(),
            'payment_id' => $this->paymentId,
            'key' => $this->generateKey(),
        ];
    }

    private function generateKey(): string
    {
        return md5($this->paymentId.$this->ticketQty.Config::get('services.library.api_key'));
    }
}
