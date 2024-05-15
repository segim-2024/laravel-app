<?php
namespace App\Repositories\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Models\MemberCashTransaction;

interface MemberCashTransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberCashDTO $DTO
     * @return MemberCashTransaction
     */
    public function save(MemberCashDTO $DTO):MemberCashTransaction;
}
