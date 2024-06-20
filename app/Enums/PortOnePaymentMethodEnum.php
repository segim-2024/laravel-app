<?php

namespace App\Enums;

use InvalidArgumentException;

enum PortOnePaymentMethodEnum:string
{
    case CARD = 'card';
    case EASY_PAY = 'easy-pay';
    case GIFT_CERTIFICATE = 'gift-certificate';
    case MOBILE = 'mobile';
    case TRANSFER = 'transfer';
    case VIRTUAL_ACCOUNT = 'virtual-account';

    public static function fromString(string $method): self
    {
        return match($method) {
            'PaymentMethodCard' => self::CARD,
            'PaymentMethodEasyPay' => self::EASY_PAY,
            'PaymentMethodGiftCertificate' => self::GIFT_CERTIFICATE,
            'PaymentMethodMobile' => self::MOBILE,
            'PaymentMethodTransfer' => self::TRANSFER,
            'PaymentMethodVirtualAccount' => self::VIRTUAL_ACCOUNT,
            default => throw new InvalidArgumentException("Invalid method: $method"),
        };
    }
}
