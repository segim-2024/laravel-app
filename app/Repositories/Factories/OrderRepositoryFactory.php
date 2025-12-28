<?php

namespace App\Repositories\Factories;

use App\Exceptions\OrderRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\WhaleOrderRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepositoryFactory
{
    /**
     * @throws OrderRepositoryFactoryException
     */
    public function create(MemberInterface $member): OrderRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleOrderRepository::class),
            $member instanceof Member => app(OrderRepository::class),
            default => throw new OrderRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }

    /**
     * isWhale 플래그로 Repository 생성
     */
    public function createByIsWhale(bool $isWhale): OrderRepositoryInterface
    {
        return $isWhale
            ? app(WhaleOrderRepository::class)
            : app(OrderRepository::class);
    }
}