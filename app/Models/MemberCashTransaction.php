<?php

namespace App\Models;

use App\Enums\MemberCashTransactionTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id ID
 * @property string $member_id 회사 ID
 * @property string $transactionable_type
 * @property int $transactionable_id
 * @property string $type 유형 (증가, 감소)
 * @property string $title 제목
 * @property int $amount 캐쉬 금액
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Member $member via member() relationship getter magic method
 * @property-read $transactionable Type of the transactionable relationship
 */
class MemberCashTransaction extends Model
{

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string, string, boolean>
     */
    protected $casts = [
        'type' => MemberCashTransactionTypeEnum::class,
    ];

    public function member():BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }

    public function transactionable():MorphTo
    {
        return $this->morphTo();
    }
}
