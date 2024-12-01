<?php

namespace App\Models;

use App\Enums\LibraryProductSubscribeStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id
 * @property int $product_id
 * @property int $card_id
 * @property LibraryProductSubscribeStateEnum $state
 * @property ?Carbon $start
 * @property ?Carbon $end
 * @property ?Carbon $due_date
 * @property int $payment_day
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Member $member
 * @property LibraryProduct $product
 * @property MemberCard $card
 */
class LibraryProductSubscribe extends Model
{
    protected $table = 'library_product_subscribes';

    protected $fillable = [
        'member_id',
        'product_id',
        'card_id',
        'state',
        'start',
        'end',
        'due_date',
        'payment_day',
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'member_id' => 'string',
        'card_id' => 'string',
        'state' => LibraryProductSubscribeStateEnum::class,
        'start' => 'date',
        'end' => 'date',
        'due_date' => 'date',
        'payment_day' => 'int',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(LibraryProduct::class, 'product_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(MemberCard::class, 'card_id');
    }
}
