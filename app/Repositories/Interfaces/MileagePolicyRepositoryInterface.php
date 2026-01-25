<?php

namespace App\Repositories\Interfaces;

use App\Models\MileagePolicy;

interface MileagePolicyRepositoryInterface
{
    /**
     * 현재 적용 중인 최신 정책 조회
     *
     * @return MileagePolicy|null
     */
    public function current(): ?MileagePolicy;
}