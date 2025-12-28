<?php

namespace App\Repositories\Factories;

use App\Exceptions\ProductRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\WhaleProductRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return ProductRepositoryInterface
     * @throws ProductRepositoryFactoryException
     */
    public function create(MemberInterface $member): ProductRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleProductRepository::class),
            $member instanceof Member => app(ProductRepository::class),
            default => throw new ProductRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}