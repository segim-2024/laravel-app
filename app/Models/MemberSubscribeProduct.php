<?php

namespace App\Models;

use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\SubscribeProductInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id 학원 ID
 * @property int $product_id 상품 ID
 * @property int $card_id 카드 ID
 * @property ?Carbon $latest_payment_at 최근 결제일
 * @property bool $is_started 시작 여부
 * @property bool $is_activated 활성화 여부
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Member $member
 * @property MemberCard $card
 * @property Product $product
 */
class MemberSubscribeProduct extends Model implements SubscribeProductInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, string, string, string>
     */
    protected $fillable = [
        'member_id',
        'product_id',
        'card_id',
        'is_started',
        'is_activated',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_started' => 'boolean',
        'is_activated' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(MemberCard::class, 'card_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMemberId(): string
    {
        return $this->member_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getCardId(): int
    {
        return $this->card_id;
    }

    public function getCard(): CardInterface
    {
        return $this->card;
    }

    public function isStarted(): bool
    {
        return $this->is_started;
    }

    public function isActivated(): bool
    {
        return $this->is_activated;
    }
}
