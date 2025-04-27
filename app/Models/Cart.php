<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property string $ct_id 카트 ID
 * @property string $od_id 카트 등록일
 * @property string $mb_id 회원 ID
 * @property string $it_id id
 * @property string $ct_price 카트 상품 가격
 * @property string $it_name 상품명
 * @property string $ct_status 상태
 * @property string $ct_qty 수량
 * @property Item $item 상품
 * @property ?OrderSegimTicketPlusLog $ticketPlusLog 티켓 로그
 * @property ?OrderSegimTicketMinusLog $ticketMinusLog 티켓 로그
 * @property Member $member 회원
 */
class Cart extends Model
{
    protected $table = "g5_shop_cart";
    protected $primaryKey = "ct_id";
    public $timestamps = false;

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'it_id', 'it_id');
    }

    public function ticketPlusLog(): HasOne
    {
        return $this->hasOne(OrderSegimTicketPlusLog::class, 'ct_id', 'ct_id');
    }

    public function ticketMinusLog(): MorphOne
    {
        return $this->morphOne(OrderSegimTicketMinusLog::class, 'cartable');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mb_id', 'mb_id');
    }
}
