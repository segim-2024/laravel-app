<?php

namespace App\Services;

use App\DTOs\CreateMemberCardDTO;
use App\DTOs\GetMemberCardApiDTO;
use App\DTOs\MemberCardDTO;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use App\Repositories\Factories\MemberCardRepositoryFactory;
use App\Repositories\Interfaces\MemberCardRepositoryInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\PortOneServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MemberCardService implements MemberCardServiceInterface {
    public function __construct(
        protected PortOneServiceInterface $portOneService,
        protected MemberCardRepositoryInterface $repository,
        protected MemberCardRepositoryFactory $repositoryFactory
    ) {}

    /**
     * @inheritDoc
     */
    public function findById(int $id): ?CardInterface
    {
        return $this->repository->findById($id);
    }

    /**
     * isWhale 플래그로 카드 조회
     */
    public function findByIdWithIsWhale(int $id, bool $isWhale): ?CardInterface
    {
        return $this->repositoryFactory->createByIsWhale($isWhale)->findById($id);
    }

    /**
     * @inheritDoc
     */
    public function find(MemberInterface $member, int|string $id): ?CardInterface
    {
        return $this->repositoryFactory->create($member)->find($member, $id);
    }

    /**
     * @inheritDoc
     */
    public function getList(MemberInterface $member): Collection
    {
        return $this->repositoryFactory->create($member)->getList($member);
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberCardDTO $DTO): CardInterface
    {
        $billingKeyDTO = $this->portOneService->getBillingKey($DTO->key);
        return $this->repositoryFactory->create($DTO->member)->save(MemberCardDTO::create($DTO, $billingKeyDTO));
    }

    /**
     * @inheritDoc
     */
    public function delete(CardInterface $card): void
    {
        $card->delete();
    }

    /**
     * @inheritDoc
     */
    public function getAllMemberCards(GetMemberCardApiDTO $DTO): LengthAwarePaginator
    {
        return $this->repositoryFactory->createByIsWhale($DTO->isWhale)->getAllMemberCardPaginate($DTO);
    }
}
