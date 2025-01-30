<?php

namespace App\Models;

use App\Models\Interfaces\OrderInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $od_id 주문번호
 * @property string $mb_id 회원아이디
 * @property string $od_name 주문자 이름
 * @property string $od_email 주문자 이메일
 * @property string $od_tel 주문자 전화번호
 * @property string $od_hp 주문자 휴대폰
 * @property string $od_zip 주문자 우편번호
 * @property string $od_addr 주문자 주소
 * @property string $od_addr2 주문자 상세주소
 * @property string $od_b_name 받는분 이름
 * @property string $od_b_hp 받는분 휴대폰
 * @property string $od_b_addr 받는분 주소
 * @property string $od_cart_price 총 상품금액
 * @property string $od_receipt_ecash 이캐쉬 사용 금액
 * @property string $od_receipt_price 입금액
 * @property string $od_receipt_point 포인트입금액
 * @property string $od_cancel_price 취소금액
 * @property string $od_misu 미수금액
 * @property string $od_send_cost 배송비
 * @property string $od_status 주문상태
 * @property string $od_time 주문일시
 * @property string $od_settle_case 결제수단
 * @property string $od_cart_count 카트 아이템 갯수
 * @property string $od_delivery_company 택배사
 * @property string $od_invoice 운송장번호
 * @property string $od_invoice_time 운송장번호 입력 시각
 * @property WhaleMember $member via member() relationship getter magic method
 */
class WhaleOrder extends Model implements OrderInterface
{
    protected $connection = "mysql_whale";
    protected $table = 'g5_shop_order';
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'od_id';


    public function member(): BelongsTo
    {
        return $this->belongsTo(WhaleMember::class, 'mb_id', 'mb_id');
    }
}
