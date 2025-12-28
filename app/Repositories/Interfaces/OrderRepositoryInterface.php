<?php
namespace App\Repositories\Interfaces;

use App\DTOs\GetMemberOrderListDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\OrderInterface;
use App\Models\Order;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string|int $id
     * @return Order|null
     */
    public function find(string|int $id): ?Order;

    /**
     * @param int|string $id
     * @param bool $isWhale
     * @return ?OrderInterface
     */
    public function findWithPlatform(int|string $id, bool $isWhale = false): ?OrderInterface;

    /**
     * @param MemberInterface $member
     * @return int
     */
    public function getTotalAmount(MemberInterface $member): int;

    /**
     * @param MemberInterface $member
     * @return int
     */
    public function getTotalPaymentCount(MemberInterface $member): int;

    /**
     * @param GetMemberOrderListDTO $DTO
     * @return mixed
     */
    public function getList(GetMemberOrderListDTO $DTO);
}
