<?php

namespace App\Services;

use App\DTOs\UpdateLibraryPaymentApiLogDTO;
use App\Models\LibraryPaymentApiLog;
use App\Repositories\Interfaces\LibraryPaymentApiLogRepositoryInterface;
use App\Services\Interfaces\LibraryPaymentApiLogServiceInterface;

class LibraryPaymentApiLogService implements LibraryPaymentApiLogServiceInterface {
    public function __construct(
        protected LibraryPaymentApiLogRepositoryInterface $repository,
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $paymentId): ?LibraryPaymentApiLog
    {
        return $this->repository->findByPaymentId($paymentId);
    }

    /**
     * @inheritDoc
     */
    public function create(string $paymentId): LibraryPaymentApiLog
    {
        return $this->repository->create([
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function update(UpdateLibraryPaymentApiLogDTO $DTO): LibraryPaymentApiLog
    {
        $log = $this->find($DTO->paymentId);
        if (! $log) {
            $log = $this->create($DTO->paymentId);
        }

        return $this->repository->update($log, $DTO->toModelAttribute());
    }
}
