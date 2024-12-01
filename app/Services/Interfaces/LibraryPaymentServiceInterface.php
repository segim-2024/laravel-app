<?php

namespace App\Services\Interfaces;

use App\DTOs\GetLibraryPaymentListDTO;

interface LibraryPaymentServiceInterface
{
    /**
     * @param GetLibraryPaymentListDTO $DTO
     */
    public function getList(GetLibraryPaymentListDTO $DTO);

    /**
     * @param string $memberId
     * @return int
     */
    public function getTotalAmount(string $memberId): int;

    /**
     * @param string $memberId
     * @return int
     */
    public function getTotalPaymentCount(string $memberId): int;
}
