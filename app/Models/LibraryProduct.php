<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $ticket_provide_qty
 * @property bool $is_hided
 * @property ?Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read LibraryProductSubscribe $subscribe
 * @property-read MorphMany|MemberPayment[] $payments
 * */
class LibraryProduct extends Model
{
    protected $fillable = [
        'name',
        'price',
        'ticket_provide_qty',
        'is_hided',
        'deleted_at',
    ];

    protected $casts = [
        'is_hided' => 'boolean',
    ];

    public function payments(): MorphMany
    {
        return $this->morphMany(MemberPayment::class, 'productable');
    }

    public function subscribe():HasOne
    {
        return $this->hasOne(LibraryProductSubscribe::class, 'product_id')
            ->where('member_id', '=', Auth::user()->mb_id);
    }
}
