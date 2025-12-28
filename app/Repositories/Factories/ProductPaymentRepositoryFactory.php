<?php

namespace App\Repositories\Factories;

use App\Models\Interfaces\MemberInterface;
use App\Repositories\Eloquent\ProductPaymentRepository;
use App\Repositories\Eloquent\WhaleProductPaymentRepository;
use App\Repositories\Interfaces\ProductPaymentRepositoryInterface;

class ProductPaymentRepositoryFactory
{
    public function create(MemberInterface $member): ProductPaymentRepositoryInterface
    {
        return $member->isWhale()
            ? app(WhaleProductPaymentRepository::class)
            : app(ProductPaymentRepository::class);
    }
}