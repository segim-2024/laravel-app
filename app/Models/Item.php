<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $it_id id
 * @property string $it_price 판매 가격
 * @property ?string $segim_ticket_type 새김 이용권 타입
 * @property string $it_cust_price 시중 가격
 * @property string $it_soldout 품절 플래그
 * @property Cart[]|Collection $carts 주문 상품들
 */
class Item extends Model
{
    protected $table = "g5_shop_item";
    protected $primaryKey = "it_id";
    protected $keyType = 'string'; // 문자열 키임을 명시
    public $incrementing = false; // 자동 증가하지 않음을 명시
    public $timestamps = false;

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'it_id');
    }
}
