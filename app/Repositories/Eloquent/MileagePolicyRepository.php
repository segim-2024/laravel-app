<?php

namespace App\Repositories\Eloquent;

use App\Models\MileagePolicy;
use App\Repositories\Interfaces\MileagePolicyRepositoryInterface;

class MileagePolicyRepository implements MileagePolicyRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function current(): ?MileagePolicy
    {
        return MileagePolicy::query()
            ->orderByDesc('policy_id')
            ->first();
    }
}