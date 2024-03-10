<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $it_id id
 * @property string $it_price 판매 가격
 * @property string $it_cust_price 시중 가격
 */
class WhaleItem extends Model
{
    protected $connection = "mysql_whale";
    protected $table = "g5_shop_item";
    protected $primaryKey = "it_id";
    protected $fillable = ["it_price", "it_cust_price"];
    public $timestamps = false;
}
