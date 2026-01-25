<?php

namespace App\Models;

use App\Models\Interfaces\MileageBalanceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $mb_no 회원 PK (g5_member.mb_no)
 * @property string $mb_id 회원 아이디 (g5_member.mb_id)
 * @property int $total_accrued 총 적립 마일리지
 * @property int $total_converted 지금까지 포인트로 전환된 누적값
 * @property int $total_used 로열티 등으로 사용한 누적값
 * @property int $balance 현재 보유 마일리지
 * @property Carbon $updated_at 마지막 업데이트 시각
 * @property Member $member via member() relationship
 */
class MileageBalance extends Model implements MileageBalanceInterface
{
    protected $table = 'mileage_balance';

    protected $primaryKey = 'mb_no';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'mb_no',
        'mb_id',
        'total_accrued',
        'total_converted',
        'total_used',
        'balance',
        'updated_at',
    ];

    protected $casts = [
        'mb_no' => 'integer',
        'mb_id' => 'string',
        'total_accrued' => 'integer',
        'total_converted' => 'integer',
        'total_used' => 'integer',
        'balance' => 'integer',
        'updated_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mb_no', 'mb_no');
    }

    public function getMbNo(): int
    {
        return $this->mb_no;
    }

    public function getMbId(): string
    {
        return $this->mb_id;
    }

    public function getTotalAccrued(): int
    {
        return $this->total_accrued;
    }

    public function getTotalConverted(): int
    {
        return $this->total_converted;
    }

    public function getTotalUsed(): int
    {
        return $this->total_used;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getConvertibleAmount(int $convertThreshold): int
    {
        return max(0, $this->balance - $convertThreshold);
    }
}
