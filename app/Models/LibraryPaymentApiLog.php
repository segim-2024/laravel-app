<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $payment_id
 * @property bool $is_success
 * @property ?string $message
 * @property ?string $status_code
 * @property ?array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property MemberPayment $payment 결제 정보
 */
class LibraryPaymentApiLog extends Model
{
    protected $fillable = [
        'payment_id',
        'is_success',
        'message',
        'status_code',
        'data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'data' => 'array',
    ];

    protected $hidden = [
        'id',
        'data',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(MemberPayment::class, 'payment_id', 'payment_id');
    }
}
