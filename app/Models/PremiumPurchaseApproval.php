<?php

namespace App\Models;

use App\Enums\PremiumPurchaseApprovalEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 프리미엄 상품 구매 승인 요청 모델
 *
 * 회원이 특정 프리미엄 상품에 대해 구매 승인을 요청하고,
 * 관리자가 승인/거절을 처리하는 기능을 담당합니다.
 *
 * @property int $id 고유 ID
 * @property string $product_id 상품 ID (영카트 it_id 참조)
 * @property string $member_id 회원 ID (그누보드 mb_id 참조)
 * @property PremiumPurchaseApprovalEnum $status 승인 상태 (pending, approved, rejected)
 * @property ?string $request_reason 승인 요청 사유
 * @property Carbon $created_at 생성일시
 * @property Carbon $updated_at 수정일시
 *
 * @property-read Item $item 상품 정보
 * @property-read Member $member 회원 정보
 */
class PremiumPurchaseApproval extends Model
{
    protected $fillable = [
        'product_id',
        'member_id',
        'status',
        'request_reason',
    ];

    protected $casts = [
        'status' => PremiumPurchaseApprovalEnum::class,
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'product_id', 'it_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }

    // 헬퍼 메서드
    public function isPending(): bool
    {
        return $this->status === PremiumPurchaseApprovalEnum::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === PremiumPurchaseApprovalEnum::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === PremiumPurchaseApprovalEnum::REJECTED;
    }

}
