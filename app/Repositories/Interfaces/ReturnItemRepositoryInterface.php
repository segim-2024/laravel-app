<?php
namespace App\Repositories\Interfaces;

use App\Models\ReturnItem;

interface ReturnItemRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $rfiId
     * @return ReturnItem|null
     */
    public function find(string $rfiId): ?ReturnItem;
}
