<?php

namespace App\Services;

use App\DTOs\GetMemberOrderListDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\OrderInterface;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Services\Interfaces\OrderServiceInterface;

class OrderService implements OrderServiceInterface {
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(int|string $id): ?Order
    {
        return $this->repository->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findWithPlatform(int|string $id, $isWhale = false): ?OrderInterface
    {
        return $this->repository->findWithPlatform($id, $isWhale);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(MemberInterface $member): int
    {
        return $this->repository->getTotalAmount($member);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(MemberInterface $member): int
    {
        return $this->repository->getTotalPaymentCount($member);
    }

    /**
     * @inheritDoc
     */
    public function getList(GetMemberOrderListDTO $DTO)
    {
        return $this->repository->getList($DTO);
    }
}
