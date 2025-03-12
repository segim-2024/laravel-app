<?php

namespace App\Services;

use App\DTOs\CreateMemberCardDTO;
use App\DTOs\GetMemberCardApiDTO;
use App\DTOs\MemberCardDTO;
use App\Models\Member;
use App\Models\MemberCard;
use App\Repositories\Interfaces\MemberCardRepositoryInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\PortOneServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MemberCardService implements MemberCardServiceInterface {
    public function __construct(
        protected PortOneServiceInterface $portOneService,
        protected MemberCardRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function findById(int $id): ?MemberCard
    {
        return $this->repository->findById($id);
    }

    /**
     * @param Member $member
     * @param int|string $id
     * @inheritDoc
     */
    public function find(Member $member, int|string $id): ?MemberCard
    {
        return $this->repository->find($member, $id);
    }

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return $this->repository->getList($member);
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberCardDTO $DTO): MemberCard
    {
        $billingKeyDTO = $this->portOneService->getBillingKey($DTO->key);
        return $this->repository->save(MemberCardDTO::create($DTO, $billingKeyDTO));
    }

    /**
     * @inheritDoc
     */
    public function delete(MemberCard $card): void
    {
        $this->repository->delete($card);
    }

    /**
     * @inheritDoc
     */
    public function getAllMemberCards(GetMemberCardApiDTO $DTO): LengthAwarePaginator
    {
        return $this->repository->getAllMemberCardPaginate($DTO);
    }
}
