<?php

namespace App\Services\Interfaces;

use App\DTOs\GetMemberOrderListDTO;
use App\Models\Interfaces\OrderInterface;
use App\Models\Member;
use App\Models\Order;

interface OrderServiceInterface
{
    /**
     * @param string|int $id
     * @return Order|null
     */
    public function find(string|int $id): ?Order;

    /**
     * @param string|int $id
     * @param $isWhale
     * @return OrderInterface|null
     */
    public function findWithPlatform(string|int $id, $isWhale = false): ?OrderInterface;

    /**
     * @param Member $member
     * @return int
     */
    public function getTotalAmount(Member $member): int;

    /**
     * @param Member $member
     * @return int
     */
    public function getTotalPaymentCount(Member $member): int;

    /**
     * @param GetMemberOrderListDTO $DTO
     */
    public function getList(GetMemberOrderListDTO $DTO);
}
