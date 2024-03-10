<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $ct_id 카트 상품 가격
 * @property string $it_id id
 * @property string $ct_price 카트 상품 가격
 * @property string $ct_status 상태
 */
class WhaleCart extends Model
{
    protected $connection = "mysql_whale";
    protected $table = "g5_shop_cart";
    protected $primaryKey = "ct_id";
    protected $fillable = ["ct_price"];
    public $timestamps = false;
}
