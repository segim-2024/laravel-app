<?php

namespace App\Services;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\UnsubscribeProductDTO;
use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Jobs\StartSubscribeSendAlimTokJob;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
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

        if ($DTO->member->mb_hp) {
            StartSubscribeSendAlimTokJob::dispatch($DTO->member)
                ->afterCommit();
        }

        /** @var Collection|Product[] $products */
        return $this->productService->getList($DTO->member);
    }

    /**
     * @inheritDoc
     */
    public function unsubscribe(UnsubscribeProductDTO $DTO): void
    {
        $this->repository->delete($DTO->subscribe);
        $this->logging(MemberSubscribeProductLogDTO::unsubscribed($DTO->subscribe));
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

    /**
     * @inheritDoc
     */
    public function isExistsSubscribed(Member $member): bool
    {
        return $this->repository->isExistsSubscribe($member);
    }
}
