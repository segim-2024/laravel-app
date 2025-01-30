<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\Models\Interfaces\MemberCashTransactionInterface;
use App\Repositories\Factories\MemberCashTransactionRepositoryFactory;
use App\Services\Interfaces\MemberCashTransactionServiceInterface;

class MemberCashTransactionService implements MemberCashTransactionServiceInterface {
    public function __construct(
        protected MemberCashTransactionRepositoryFactory $repositoryFactory,
    ) {}

    /**
     * @inheritDoc
     */
    public function create(MemberCashDTO $DTO): MemberCashTransactionInterface
    {
        return $this->repositoryFactory->create($DTO->member)->save($DTO);
    }
}
