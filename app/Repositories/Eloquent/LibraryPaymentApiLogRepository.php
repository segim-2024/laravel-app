<?php
namespace App\Repositories\Eloquent;

use App\Models\LibraryPaymentApiLog;
use App\Repositories\Interfaces\LibraryPaymentApiLogRepositoryInterface;

class LibraryPaymentApiLogRepository extends BaseRepository implements LibraryPaymentApiLogRepositoryInterface
{
    public function __construct(LibraryPaymentApiLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByPaymentId(string $paymentId): ?LibraryPaymentApiLog
    {
        return LibraryPaymentApiLog::where('payment_id', $paymentId)->first();
    }
}
