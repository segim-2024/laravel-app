<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $ct_id 장바구니 항목 ID
 * @property int $od_id 주문 ID
 * @property string $it_id 상품 ID
 * @property string $mb_id 회원 ID
 * @property string $ct_status 장바구니 상태
 * @property string $it_name 상품명
 * @property string $ct_send_cost 배송비
 * @property string $ct_point 포인트
 * @property int $ct_qty 상품 수량
 * @property string $ct_option 상품 옵션
 * @property string $ct_select 옵션 선택
 * @property float $ct_price 상품 가격
 * @property string $ct_time 장바구니에 담긴 시간
 *
 * @property Order $order via order() relationship getter magic method
 * @property Item $item via item() relationship getter magic method
 */
class OrderCart extends Model
{
    protected $table = 'g5_shop_item';
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ct_id';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'od_id', 'od_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'it_id', 'it_id');
    }
}
