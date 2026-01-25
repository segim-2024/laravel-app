<?php

namespace App\Repositories\Eloquent;

use App\Models\Interfaces\MileageBalanceInterface;
use App\Models\MileageBalance;
use App\Repositories\Interfaces\MileageBalanceRepositoryInterface;

class MileageBalanceRepository implements MileageBalanceRepositoryInterface
{
    public function findByMbNo(int $mbNo): ?MileageBalanceInterface
    {
        return MileageBalance::find($mbNo);
    }

    public function findByMbId(string $mbId): ?MileageBalanceInterface
    {
        return MileageBalance::where('mb_id', $mbId)->first();
    }
}
