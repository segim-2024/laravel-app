<?php

namespace App\Enums;

enum PremiumPurchaseApprovalEnum:string
{
    case PENDING = 'pending';     // 승인 신청
    case APPROVED = 'approved';   // 승인 완료
    case REJECTED = 'rejected';   // 승인 거절

    /**
     * 한국어 라벨 반환
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => '승인 신청',
            self::APPROVED => '승인 완료',
            self::REJECTED => '승인 거절',
        };
    }
}
