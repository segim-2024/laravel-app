<?php

namespace App\Models;

use App\Models\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name 상품명
 * @property string $payment_day 결제일
 * @property int $price 금액
 * @property bool $is_used 사용 여부
 * @property bool $is_deleted 삭제 여부
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property WhaleMemberSubscribeProduct $subscribe
 * @property Collection|WhaleMemberSubscribeProduct[] $subscribes
 */
class WhaleProduct extends Model implements ProductInterface
{
    protected $connection = 'mysql_whale';
    protected $table = 'products';

    protected $casts = [
        'name' => 'string',
        'payment_day' => 'string',
        'price' => 'integer',
        'is_used' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function payments(): MorphMany
    {
        return $this->morphMany(WhaleMemberPayment::class, 'productable');
    }

    public function subscribe(): HasOne
    {
        return $this->hasOne(WhaleMemberSubscribeProduct::class, 'product_id')
            ->where('member_id', '=', Auth::user()->mb_id);
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(WhaleMemberSubscribeProduct::class, 'product_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPaymentDay(): string
    {
        return $this->payment_day;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
