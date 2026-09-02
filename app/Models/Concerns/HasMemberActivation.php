<?php

namespace App\Models\Concerns;

/**
 * 그누보드 회원의 이용 가능 여부 판정.
 *
 * 파머스/고래 g5_member 가 동일한 컬럼 구조를 갖는다.
 */
trait HasMemberActivation
{
    /**
     * 탈퇴하거나 차단되지 않은 회원인지 여부.
     */
    public function isActive(): bool
    {
        return $this->withdrawn_at === null
            && (string) $this->mb_leave_date === ''
            && (string) $this->mb_intercept_date === '';
    }
}
