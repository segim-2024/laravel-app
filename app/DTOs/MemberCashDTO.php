<?php

namespace App\DTOs;

use App\Enums\MemberCashTransactionTypeEnum;
use App\Http\Requests\CreateMemberCashOrderRequest;
use App\Http\Requests\ECashManualChargeRequest;
use App\Http\Requests\ECashManualSpendRequest;
use App\Http\Requests\MemberCashManualChargeRequest;
use App\Http\Requests\MemberCashManualSpendRequest;
use App\Models\Interfaces\MemberInterface;
use App\Models\Order;
use App\Models\Product;

class MemberCashDTO
{
    public function __construct(
        public readonly MemberInterface $member,
        public readonly int $amount,
        public readonly MemberCashTransactionTypeEnum $type,
        public readonly string $title,
        public readonly Product|Order|null $transactionable,
    ) {}

    public static function createFromMemberCashOrderRequest(CreateMemberCashOrderRequest $request):self
    {
        return new self(
            $request->member,
            $request->validated('amount'),
            MemberCashTransactionTypeEnum::Decreased,
            $request->validated('title'),
            $request->order
        );
    }

    public static function createFromMemberCashManualSpendRequest(MemberCashManualSpendRequest $request):self
    {
        return new self(
            $request->member,
            $request->validated('amount'),
            MemberCashTransactionTypeEnum::Decreased,
            $request->validated('title'),
            null
        );
    }

    public static function createFromMemberCashManualChargeRequest(MemberCashManualChargeRequest $request):self
    {
        return new self(
            $request->member,
            $request->validated('amount'),
            MemberCashTransactionTypeEnum::Increased,
            $request->validated('title'),
            null
        );
    }

    public static function createFromECashManualChargeRequest(ECashManualChargeRequest $request): self
    {
        return new self(
            $request->user(),
            $request->validated('amount'),
            MemberCashTransactionTypeEnum::Increased,
            $request->validated('title'),
            null
        );
    }

    public static function createFromECashManualSpendRequest(ECashManualSpendRequest $request): self
    {
        return new self(
            $request->user(),
            $request->validated('amount'),
            MemberCashTransactionTypeEnum::Decreased,
            $request->validated('title'),
            null
        );
    }
}
