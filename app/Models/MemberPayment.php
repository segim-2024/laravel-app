<?php

namespace App\Models;

use App\Enums\MemberPaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;


/**
 * @property int $id
 * @property string $tx_id 거래번호
 * @property string $payment_id 결제 고유 키
 * @property string $member_id 학원 ID
 * @property int $card_id 학원 ID
 * @property MemberPaymentStatusEnum $state 결제 상태
 * @property ?string $method 결제 방식
 * @property string $title 결제 제목
 * @property int $amount 결제 금액
 * @property ?int $cancelled_amount 결제 금액
 * @property string $reason 결제 제목
 * @property ?string $api API 응답 데이터
 * @property ?string $receipt_url 영수증 URL
 * @property Carbon|null $paid_at
 * @property Carbon|null $cancelled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Member $member via member() relationship getter magic method
 * @property MemberCard $card via card() relationship getter magic method
 * @property-read Product|LibraryProduct|null $productable Type of the productable relationship
 */
class MemberPayment extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string, string>
     */
    protected $hidden = [
        'tx_id',
        'api'
    ];

    protected $casts = [
        'state' => MemberPaymentStatusEnum::class,
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(MemberCard::class, 'card_id');
    }

    public function productable(): MorphTo
    {
        return $this->morphTo();
    }
}
