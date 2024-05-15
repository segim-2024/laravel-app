<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\Models\MemberCashTransaction;
use App\Repositories\Interfaces\MemberCashTransactionRepositoryInterface;
use App\Services\Interfaces\MemberCashTransactionServiceInterface;

class MemberCashTransactionService implements MemberCashTransactionServiceInterface {
    public function __construct(
        protected MemberCashTransactionRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function create(MemberCashDTO $DTO): MemberCashTransaction
    {
        return $this->repository->save($DTO);
    }
}
