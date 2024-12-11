<?php
namespace App\Repositories\Interfaces;

use App\Models\LibraryPaymentApiLog;

interface LibraryPaymentApiLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $paymentId
     * @return LibraryPaymentApiLog|null
     */
    public function findByPaymentId(string $paymentId): ?LibraryPaymentApiLog;
}
