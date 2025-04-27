<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property string $rfi_id 반품 상품 ID
 * @property string $rf_id 반품 ID
 * @property string $mb_id 회원 ID
 * @property string $od_id 주문 ID
 * @property string $ct_id 카트 ID
 * @property int $qty 수량
 * @property Cart $cart 상품
 * @property Member $member 회원
 * @property ?OrderSegimTicketMinusLog $ticketMinusLog 티켓 로그
 */
class ReturnItem extends Model
{
    protected $table = 'g5_shop_return_item';
    protected $primaryKey = 'rfi_id';
    public $timestamps = false;

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'ct_id', 'ct_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mb_id', 'mb_id');
    }

    public function ticketMinusLog(): MorphOne
    {
        return $this->morphOne(OrderSegimTicketMinusLog::class, 'cartable');
    }
}
