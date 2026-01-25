<?php

namespace App\Models;

use App\Enums\MileageActionEnum;
use App\Enums\MileageChannelEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $history_id 마일리지 이력 PK
 * @property int $mb_no 회원 PK
 * @property string $mb_id 회원 아이디
 * @property MileageActionEnum $action 액션 타입
 * @property int $change_amount 변경된 마일리지 값
 * @property int $balance_before 변경 전 잔액
 * @property int $balance_after 변경 후 잔액
 * @property int $point_change 포인트 변화량
 * @property string $context_type 변경 발생 기능/맥락
 * @property ?string $context_key 맥락별 식별값
 * @property MileageChannelEnum $channel 채널
 * @property ?string $admin_id 관리자 ID
 * @property ?string $memo 메모
 * @property Carbon $created_at 기록 시각
 * @property Member $member via member() relationship
 */
class MileageHistory extends Model
{
    protected $table = 'mileage_history';

    protected $primaryKey = 'history_id';

    public $timestamps = false;

    protected $fillable = [
        'mb_no',
        'mb_id',
        'action',
        'change_amount',
        'balance_before',
        'balance_after',
        'point_change',
        'context_type',
        'context_key',
        'channel',
        'admin_id',
        'memo',
        'created_at',
    ];

    protected $casts = [
        'history_id' => 'integer',
        'mb_no' => 'integer',
        'mb_id' => 'string',
        'action' => MileageActionEnum::class,
        'change_amount' => 'integer',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
        'point_change' => 'integer',
        'context_type' => 'string',
        'context_key' => 'string',
        'channel' => MileageChannelEnum::class,
        'admin_id' => 'string',
        'memo' => 'string',
        'created_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mb_no', 'mb_no');
    }

    /**
     * 설명 텍스트 반환 (context_type + context_key 또는 memo)
     */
    public function getDescriptionAttribute(): string
    {
        if ($this->context_key) {
            return $this->context_type . ' ' . $this->context_key;
        }

        if ($this->memo) {
            return $this->memo;
        }

        return $this->context_type;
    }

    /**
     * 금액을 포맷팅해서 반환 (+1,000 / -1,000)
     */
    public function getFormattedAmountAttribute(): string
    {
        $amount = $this->change_amount;

        if ($amount >= 0) {
            return '+' . number_format($amount);
        }

        return number_format($amount);
    }
}