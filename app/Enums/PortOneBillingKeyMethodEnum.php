<?php

namespace App\Enums;

use InvalidArgumentException;

enum PortOneBillingKeyMethodEnum:string
{
    CASE CARD = 'card';
    case EASY_PAY = 'easy-pay';
    case MOBILE = 'mobile';

    public static function fromString(string $method): self
    {
        return match($method) {
            'BillingKeyPaymentMethodCard' => self::CARD,
            'BillingKeyPaymentMethodEasyPay' => self::EASY_PAY,
            'BillingKeyPaymentMethodMobile' => self::MOBILE,
            default => throw new InvalidArgumentException("Invalid method: $method"),
        };
    }
}
