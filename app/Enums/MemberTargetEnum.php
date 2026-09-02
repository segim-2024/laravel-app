<?php

namespace App\Enums;

use App\Models\Interfaces\MemberInterface;

/**
 * 회원이 속한 시스템 구분.
 */
enum MemberTargetEnum: string
{
    case Pamus = 'pamus';
    case Whale = 'whale';

    public function isWhale(): bool
    {
        return $this === self::Whale;
    }

    public static function fromMember(MemberInterface $member): self
    {
        return $member->isWhale() ? self::Whale : self::Pamus;
    }

    /**
     * 라우트 파라미터 제약용 값 목록
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
