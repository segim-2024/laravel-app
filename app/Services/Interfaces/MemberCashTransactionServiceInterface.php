<?php

namespace App\Services\Interfaces;

use App\DTOs\GetECashHistoryDTO;
use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashTransactionRepositoryFactoryException;
use App\Models\Interfaces\MemberCashTransactionInterface;
use Illuminate\Support\Collection;

interface MemberCashTransactionServiceInterface
{
    /**
     * @param MemberCashDTO $DTO
     * @return MemberCashTransactionInterface
     * @throws MemberCashTransactionRepositoryFactoryException
     */
    public function create(MemberCashDTO $DTO): MemberCashTransactionInterface;

    /**
     * @param GetECashHistoryDTO $DTO
     * @return Collection
     * @throws MemberCashTransactionRepositoryFactoryException
     */
    public function excel(GetECashHistoryDTO $DTO): Collection;

}
