<?php
namespace App\Repositories\Interfaces;

use App\DTOs\GetECashHistoryDTO;
use App\DTOs\MemberCashDTO;
use App\Models\Interfaces\MemberCashTransactionInterface;
use Illuminate\Support\Collection;

interface MemberCashTransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberCashDTO $DTO
     * @return MemberCashTransactionInterface
     */
    public function save(MemberCashDTO $DTO): MemberCashTransactionInterface;

    /**
     * @param GetECashHistoryDTO $DTO
     * @return Collection
     */
    public function excel(GetECashHistoryDTO $DTO): Collection;
}
