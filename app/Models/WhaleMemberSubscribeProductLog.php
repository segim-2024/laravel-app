<?php

namespace App\Models;

use App\Enums\MemberSubscribeProductLogEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $subscribe_id
 * @property MemberSubscribeProductLogEnum $type
 * @property string|null $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class WhaleMemberSubscribeProductLog extends Model
{
    protected $connection = 'mysql_whale';
    protected $table = 'member_subscribe_product_logs';

    protected $fillable = [
        'subscribe_id',
        'type',
        'message',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => MemberSubscribeProductLogEnum::class,
    ];

    public function subscribe(): BelongsTo
    {
        return $this->belongsTo(WhaleMemberSubscribeProduct::class, 'subscribe_id');
    }
}