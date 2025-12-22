<?php

namespace App\Models;

use App\Models\Interfaces\SubscribeProductInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id 회원 ID
 * @property int $product_id 상품 ID
 * @property int $card_id 카드 ID
 * @property ?Carbon $latest_payment_at 최근 결제일
 * @property bool $is_started 시작 여부
 * @property bool $is_activated 활성화 여부
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property WhaleMember $member
 * @property WhaleMemberCard $card
 * @property WhaleProduct $product
 */
class WhaleMemberSubscribeProduct extends Model implements SubscribeProductInterface
{
    protected $connection = 'mysql_whale';
    protected $table = 'member_subscribe_products';

    protected $fillable = [
        'member_id',
        'product_id',
        'card_id',
        'is_started',
        'is_activated',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_started' => 'boolean',
        'is_activated' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(WhaleMember::class, 'member_id', 'mb_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(WhaleMemberCard::class, 'card_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(WhaleProduct::class, 'product_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WhaleMemberSubscribeProductLog::class, 'subscribe_id');
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

    public function isStarted(): bool
    {
        return $this->is_started;
    }

    public function isActivated(): bool
    {
        return $this->is_activated;
    }
}