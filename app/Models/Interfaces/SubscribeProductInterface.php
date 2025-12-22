<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface SubscribeProductInterface
{
    public function getId(): int;

    public function getMemberId(): string;

    public function getProductId(): int;

    public function getCardId(): int;

    public function isStarted(): bool;

    public function isActivated(): bool;

    public function member(): BelongsTo;

    public function card(): BelongsTo;

    public function product(): BelongsTo;
}