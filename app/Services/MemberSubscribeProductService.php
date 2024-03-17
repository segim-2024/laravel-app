<?php

namespace App\Services;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Support\Collection;

class MemberSubscribeProductService implements MemberSubscribeProductServiceInterface {
    public function __construct(
        protected ProductServiceInterface $productService,
        protected MemberSubscribeProductRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function subscribe(UpsertMemberSubscribeProductDTO $DTO): Collection
    {
        $this->repository->upsertCard($DTO);

        /** @var Collection|Product[] $products */
        return $this->productService->getList($DTO->member);
    }
}
