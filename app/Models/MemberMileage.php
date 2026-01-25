<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $mb_no 회원 PK (g5_member.mb_no)
 * @property string $mb_id 회원 아이디 (g5_member.mb_id)
 * @property bool $is_target 1=마일리지 대상자, 0=미대상/해지
 * @property ?string $memo 관리자 메모
 * @property ?Carbon $updated_at 마지막 변경 시각
 * @property Member $member via member() relationship
 */
class MemberMileage extends Model
{
    protected $table = 'mileage_member';

    protected $primaryKey = 'mb_no';

    public $incrementing = false;

    public $timestamps = false;

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'mb_no',
        'mb_id',
        'is_target',
        'memo',
    ];

    protected $casts = [
        'mb_no' => 'integer',
        'mb_id' => 'string',
        'is_target' => 'boolean',
        'memo' => 'string',
        'updated_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mb_no', 'mb_no');
    }

    /**
     * 마일리지 대상자인지 확인
     */
    public function isTarget(): bool
    {
        return $this->is_target === true;
    }
}