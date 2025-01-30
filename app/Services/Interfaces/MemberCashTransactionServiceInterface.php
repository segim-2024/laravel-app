<?php

namespace App\Services\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashTransactionRepositoryFactoryException;
use App\Models\Interfaces\MemberCashTransactionInterface;

interface MemberCashTransactionServiceInterface
{
    /**
     * @param MemberCashDTO $DTO
     * @return MemberCashTransactionInterface
     * @throws MemberCashTransactionRepositoryFactoryException
     */
    public function create(MemberCashDTO $DTO): MemberCashTransactionInterface;
}
