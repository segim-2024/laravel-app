<?php

namespace App\Services;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Support\Collection;

class MemberSubscribeProductService implements MemberSubscribeProductServiceInterface {
    public function __construct(
        protected ProductServiceInterface $productService,
        protected MemberSubscribeProductRepositoryInterface $repository,
        protected MemberSubscribeProductLogRepositoryInterface $logRepository
    ) {}

    /**
     * @inheritDoc
     */
    public function findByMemberAndProduct(Member $member, Product $product): ?MemberSubscribeProduct
    {
        return $this->repository->findByMemberAndProduct($member, $product);
    }

    /**
     * @inheritDoc
     */
    public function subscribe(UpsertMemberSubscribeProductDTO $DTO): Collection
    {
        $subscribe = $this->repository->upsertCard($DTO);
        $this->logging(MemberSubscribeProductLogDTO::subscribed($subscribe));

        /** @var Collection|Product[] $products */
        return $this->productService->getList($DTO->member);
    }

    /**
     * @inheritDoc
     */
    public function updateLatestPayment(MemberSubscribeProduct $subscribeProduct): MemberSubscribeProduct
    {
        return $this->repository->updateLatestPayment($subscribeProduct);
    }

    /**
     * @inheritDoc
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): MemberSubscribeProduct
    {
        $logDTO = $DTO->isActive ? MemberSubscribeProductLogDTO::activated($DTO->subscribe) : MemberSubscribeProductLogDTO::deactivated($DTO->subscribe);
        $this->logging($logDTO);
        return $this->repository->updateActivate($DTO);
    }

    /**
     * @inheritDoc
     */
    public function logging(MemberSubscribeProductLogDTO $DTO):void
    {
        $this->logRepository->save($DTO);
    }
}
