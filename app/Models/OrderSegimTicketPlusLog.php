<?php

namespace App\Models;

use App\Enums\SegimTicketTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $mb_id 회원 ID
 * @property string $od_id 주문 ID
 * @property string $it_id 상품 ID
 * @property string $ct_id 카트 ID
 * @property string $ct_qty 수량
 * @property SegimTicketTypeEnum $ticket_type 티켓 타입
 * @property string $api API
 * @property Cart $cart 카트
 **/
class OrderSegimTicketPlusLog extends Model
{
    protected $table = 'order_segim_ticket_plus_logs';

    protected $fillable = [
        'mb_id',
        'od_id',
        'it_id',
        'ct_id',
        'ct_qty',
        'ticket_type',
        'api',
    ];

    protected $casts = [
        'ticket_type' => SegimTicketTypeEnum::class,
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'ct_id', 'ct_id');
    }
}
