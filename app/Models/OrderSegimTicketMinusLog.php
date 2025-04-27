<?php

namespace App\Models;

use App\Enums\SegimTicketTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id 회원 ID
 * @property string $mb_id 회원 ID
 * @property string $cartable_type 반품 가능 테이블 유형
 * @property string $cartable_id 반품 가능 테이블 ID
 * @property int $qty 수량
 * @property string $it_id 상품 ID
 * @property SegimTicketTypeEnum $ticket_type 티켓 타입
 * @property string $api API
 * @property Cart $cart 카트
 * @property Member $member 회원
 * @property Cart|ReturnItem $cartable 반품 가능 테이블
 */
class OrderSegimTicketMinusLog extends Model
{
    protected $table = 'order_segim_ticket_minus_logs';

    protected $fillable = [
        'mb_id',
        'cartable_type',
        'cartable_id',
        'cartable_id',
        'qty',
        'it_id',
        'ticket_type',
        'api',
    ];

    protected $casts = [
        'ticket_type' => SegimTicketTypeEnum::class,
    ];

    public function cartable(): MorphTo
    {
        return $this->morphTo();
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mb_id', 'mb_id');
    }
}
