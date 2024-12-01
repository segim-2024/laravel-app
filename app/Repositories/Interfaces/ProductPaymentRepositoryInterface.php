<?php
namespace App\Repositories\Interfaces;

use App\DTOs\GetMemberPaymentListDTO;

interface ProductPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param GetMemberPaymentListDTO $DTO
     */
    public function getList(GetMemberPaymentListDTO $DTO);

    /**
     * @param string $memberId
     * @return int
     */
    public function getTotalAmount(string $memberId): int;

    /**
     * @param string $memberId
     * @return int
     */
    public function getTotalPaymentCount(string $memberId): int;
}
