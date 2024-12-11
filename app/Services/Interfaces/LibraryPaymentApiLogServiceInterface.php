<?php

namespace App\Services\Interfaces;

use App\DTOs\UpdateLibraryPaymentApiLogDTO;
use App\Models\LibraryPaymentApiLog;

interface LibraryPaymentApiLogServiceInterface
{
    /**
     * @param string $paymentId
     * @return LibraryPaymentApiLog|null
     */
    public function find(string $paymentId): ?LibraryPaymentApiLog;

    /**
     * @param string $paymentId
     * @return LibraryPaymentApiLog
     */
    public function create(string $paymentId): LibraryPaymentApiLog;

    /**
     * @param UpdateLibraryPaymentApiLogDTO $DTO
     * @return LibraryPaymentApiLog
     */
    public function update(UpdateLibraryPaymentApiLogDTO $DTO): LibraryPaymentApiLog;
}
