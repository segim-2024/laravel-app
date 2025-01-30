<?php
namespace App\Repositories\Interfaces;

use App\DTOs\MemberCashDTO;
use App\Models\Interfaces\MemberCashTransactionInterface;

interface MemberCashTransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberCashDTO $DTO
     * @return MemberCashTransactionInterface
     */
    public function save(MemberCashDTO $DTO): MemberCashTransactionInterface;
}
