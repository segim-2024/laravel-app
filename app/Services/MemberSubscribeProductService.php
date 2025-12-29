<?php

namespace App\Services;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\UnsubscribeProductDTO;
use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Jobs\StartSubscribeSendAlimTokJob;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Models\Interfaces\SubscribeProductInterface;
use App\Repositories\Factories\MemberSubscribeProductLogRepositoryFactory;
use App\Repositories\Factories\MemberSubscribeProductRepositoryFactory;
use App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Support\Collection;

class MemberSubscribeProductService implements MemberSubscribeProductServiceInterface {
    public function __construct(
        protected ProductServiceInterface $productService,
        protected MemberSubscribeProductRepositoryInterface $repository,
        protected MemberSubscribeProductLogRepositoryInterface $logRepository,
        protected MemberSubscribeProductRepositoryFactory $repositoryFactory,
        protected MemberSubscribeProductLogRepositoryFactory $logRepositoryFactory
    ) {}

    /**
     * @inheritDoc
     */
    public function findByMemberAndProduct(MemberInterface $member, ProductInterface $product): ?SubscribeProductInterface
    {
        return $this->repositoryFactory->create($member)->findByMemberAndProduct($member, $product);
    }

    /**
     * @inheritDoc
     */
    public function subscribe(UpsertMemberSubscribeProductDTO $DTO): Collection
    {
        $subscribe = $this->repositoryFactory->create($DTO->member)->upsertCard($DTO);
        $this->logging($DTO->member, MemberSubscribeProductLogDTO::subscribed($subscribe));

        if ($DTO->member->mb_hp && ! $DTO->member->isWhale()) {
            StartSubscribeSendAlimTokJob::dispatch($DTO->member)
                ->afterCommit();
        }

        return $this->productService->getList($DTO->member);
    }

    /**
     * @inheritDoc
     */
    public function unsubscribe(UnsubscribeProductDTO $DTO): void
    {
        // 삭제 전에 로깅 (고래영어는 mysql_whale 연결로 즉시 커밋되므로 삭제 후 로깅 시 FK 에러 발생)
        $this->logging($DTO->member, MemberSubscribeProductLogDTO::unsubscribed($DTO->subscribe));
        $this->repositoryFactory->create($DTO->member)->delete($DTO->subscribe);
    }

    /**
     * @inheritDoc
     */
    public function updateLatestPayment(MemberInterface $member, SubscribeProductInterface $subscribeProduct): SubscribeProductInterface
    {
        return $this->repositoryFactory->create($member)->updateLatestPayment($subscribeProduct);
    }

    /**
     * @inheritDoc
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): SubscribeProductInterface
    {
        $logDTO = $DTO->isActive ? MemberSubscribeProductLogDTO::activated($DTO->subscribe) : MemberSubscribeProductLogDTO::deactivated($DTO->subscribe);
        $this->logging($DTO->member, $logDTO);
        return $this->repositoryFactory->create($DTO->member)->updateActivate($DTO);
    }

    /**
     * @inheritDoc
     */
    public function logging(MemberInterface $member, MemberSubscribeProductLogDTO $DTO): void
    {
        $this->logRepositoryFactory->create($member)->save($DTO);
    }

    /**
     * @inheritDoc
     */
    public function isExistsSubscribed(MemberInterface $member): bool
    {
        return $this->repositoryFactory->create($member)->isExistsSubscribe($member);
    }
}
