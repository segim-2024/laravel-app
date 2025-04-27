<?php

namespace App\Services\Interfaces;

use App\Models\ReturnItem;

interface ReturnItemServiceInterface
{
    /**
     * @param string $rfiId
     * @return ReturnItem|null
     */
    public function find(string $rfiId): ?ReturnItem;
}
