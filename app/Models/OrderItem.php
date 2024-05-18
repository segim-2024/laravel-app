<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $it_id 상품 ID
 * @property string $it_name 상품명
 * @property string $it_description 상품 설명
 * @property float $it_price 상품 가격
 * @property string $it_image 상품 이미지 URL
 * @property int $it_stock 상품 재고
 *
 * @property Collection|Cart[] $carts
 */
class OrderItem extends Model
{
    protected $table = 'g5_shop_item';
    protected $primaryKey = 'it_id';
    public $timestamps = false;

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'it_id', 'it_id');
    }
}
