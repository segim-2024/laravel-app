<?php

namespace App\Models\Interfaces;

use App\Enums\MemberPaymentStatusEnum;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface PaymentInterface
{
    public function getId(): int;

    public function getPaymentId(): string;

    public function getMemberId(): string;

    public function getCardId(): ?int;

    public function getState(): MemberPaymentStatusEnum;

    public function getAmount(): int;

    public function getTitle(): string;

    public function getMember(): MemberInterface;

    public function productable(): MorphTo;
}