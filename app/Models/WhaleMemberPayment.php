<?php

namespace App\Models;

use App\Enums\MemberPaymentStatusEnum;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\PaymentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $payment_id 결제 고유 키
 * @property string $member_id 회원 ID
 * @property int $card_id 카드 ID
 * @property MemberPaymentStatusEnum $state 결제 상태
 * @property ?string $method 결제 방식
 * @property string $title 결제 제목
 * @property int $amount 결제 금액
 * @property ?int $cancelled_amount 취소 금액
 * @property string $reason 취소/실패 사유
 * @property ?string $api API 응답 데이터
 * @property ?string $receipt_url 영수증 URL
 * @property ?string $payment_key PG사 결제키
 * @property Carbon|null $paid_at
 * @property Carbon|null $cancelled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property WhaleMember $member via member() relationship getter magic method
 * @property WhaleMemberCard $card via card() relationship getter magic method
 * @property-read WhaleProduct|null $productable Type of the productable relationship
 */
class WhaleMemberPayment extends Model implements PaymentInterface
{
    protected $connection = 'mysql_whale';
    protected $table = 'member_payments';

    protected $hidden = [
        'api'
    ];

    protected $casts = [
        'state' => MemberPaymentStatusEnum::class,
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(WhaleMember::class, 'member_id', 'mb_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(WhaleMemberCard::class, 'card_id');
    }

    public function productable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPaymentId(): string
    {
        return $this->payment_id;
    }

    public function getMemberId(): string
    {
        return $this->member_id;
    }

    public function getCardId(): ?int
    {
        return $this->card_id;
    }

    public function getState(): MemberPaymentStatusEnum
    {
        return $this->state;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMember(): MemberInterface
    {
        return $this->member;
    }
}