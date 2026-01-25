<?php

namespace App\Repositories\Interfaces;

use App\DTOs\GetMileageHistoryListDTO;
use Illuminate\Http\JsonResponse;

interface MileageHistoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 마일리지 이력 목록 조회 (DataTables)
     */
    public function getList(GetMileageHistoryListDTO $DTO): JsonResponse;
}
