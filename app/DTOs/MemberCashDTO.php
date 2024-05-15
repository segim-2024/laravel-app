<?php

namespace App\DTOs;

use App\Models\Member;

class MemberCashDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly int $amount,
        public readonly CompanyPointTransactionTypeEnum $type,
        public readonly string $title,
        public readonly CompanyPayment|GiftShopOrder|GiftShopOrderItem|null $transactionable,
    ) {}
}
