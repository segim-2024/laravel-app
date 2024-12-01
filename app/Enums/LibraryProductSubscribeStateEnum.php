<?php

namespace App\Enums;

enum LibraryProductSubscribeStateEnum: string
{
    // 상태: 사용중
    case Subscribe = 'subscribe';
    // 상태: 미납
    case Unpaid = 'unpaid';
    // 상태: 해지됨
    case Unsubscribe = 'unsubscribe';

    public function isSubscribe(): bool
    {
        return $this === self::Subscribe;
    }

    public function isUnpaid(): bool
    {
        return $this === self::Unpaid;
    }

    public function isUnsubscribe(): bool
    {
        return $this === self::Unsubscribe;
    }

    public function toKorean(): string
    {
        return match ($this) {
            self::Subscribe => '사용중',
            self::Unpaid => '미납',
            self::Unsubscribe => '해지됨',
        };
    }
}
