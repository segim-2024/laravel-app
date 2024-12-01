<?php

namespace App\Services;

use App\DTOs\GetLibraryPaymentListDTO;
use App\Repositories\Interfaces\LibraryPaymentRepositoryInterface;
use App\Services\Interfaces\LibraryPaymentServiceInterface;

class LibraryPaymentService implements LibraryPaymentServiceInterface {
    public function __construct(
        protected LibraryPaymentRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function getList(GetLibraryPaymentListDTO $DTO)
    {
        return $this->repository->getList($DTO);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(string $memberId): int
    {
        return $this->repository->getTotalAmount($memberId);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(string $memberId): int
    {
        return $this->repository->getTotalPaymentCount($memberId);
    }
}
