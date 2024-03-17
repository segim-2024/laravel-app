<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id 학원 ID
 * @property int $product_id 상품 ID
 * @property int $card_id 카드 ID
 * @property ?Carbon $latest_payment_at 최근 결제일
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Member $member
 * @property MemberCard $card
 * @property Product $product
 */
class MemberSubscribeProduct extends Model
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
        'created_at',
        'updated_at',
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
}
