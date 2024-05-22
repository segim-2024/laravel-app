<?php

namespace App\Models;

use App\Enums\MemberSubscribeProductLogEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $subscribe_id
 * @property string $member_id
 * @property int $product_id
 * @property int $card_id
 * @property MemberSubscribeProductLogEnum $type
 * @property string|null $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class MemberSubscribeProductLog extends Model
{
    protected $fillable = [
        'subscribe_id',
        'member_id',
        'product_id',
        'card_id',
        'type',
        'content',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => MemberSubscribeProductLogEnum::class,
    ];

    public function subscribe(): BelongsTo
    {
        return $this->belongsTo(MemberSubscribeProduct::class, 'subscribe_id');
    }

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
