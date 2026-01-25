<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $policy_id 정책 PK
 * @property float $accrual_rate 마일리지 적립율 (%)
 * @property int $min_month_sales 유효 월 매출 최소 기준금액
 * @property int $convert_threshold 포인트 전환 가능 기준금액
 * @property Carbon $created_at 정책 생성 시각
 */
class MileagePolicy extends Model
{
    protected $table = 'mileage_policy';

    protected $primaryKey = 'policy_id';

    public $timestamps = false;

    protected $fillable = [
        'accrual_rate',
        'min_month_sales',
        'convert_threshold',
        'created_at',
    ];

    protected $casts = [
        'policy_id' => 'integer',
        'accrual_rate' => 'decimal:2',
        'min_month_sales' => 'integer',
        'convert_threshold' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * 현재 적용 중인 최신 정책 조회
     */
    public static function current(): ?self
    {
        return static::query()
            ->orderByDesc('policy_id')
            ->first();
    }

    /**
     * 적립율을 퍼센트 문자열로 반환 (예: "5%")
     */
    public function getAccrualRatePercentAttribute(): string
    {
        $rate = floatval($this->accrual_rate);

        // 소수점이 .00이면 정수로 표시
        if ($rate == intval($rate)) {
            return intval($rate) . '%';
        }

        return $rate . '%';
    }
}