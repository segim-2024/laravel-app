<?php

namespace App\Services\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Models\MemberCashTransaction;

interface MemberCashTransactionServiceInterface
{
    /**
     * @param MemberCashDTO $DTO
     * @return MemberCashTransaction
     */
    public function create(MemberCashDTO $DTO): MemberCashTransaction;
}
