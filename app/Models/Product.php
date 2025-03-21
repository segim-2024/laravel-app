<?php

namespace App\Models;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property MemberSubscribeProduct $subscribe
 * @property Collection|MemberSubscribeProduct[] $subscribes
 */
class Product extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string, string, string>
     */
    protected $casts = [
        'name' => 'string',
        'payment_day' => 'string',
        'price' => 'integer',
    ];

    public function payments(): MorphMany
    {
        return $this->morphMany(MemberPayment::class, 'productable');
    }

    public function subscribe():HasOne
    {
        return $this->hasOne(MemberSubscribeProduct::class, 'product_id')
            ->where('member_id', '=', Auth::user()->mb_id);
    }

    public function subscribes():HasMany
    {
        return $this->hasMany(MemberSubscribeProduct::class, 'product_id');
    }
}
