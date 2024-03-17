<?php

namespace App\Services;

use App\DTOs\CreateMemberCardDTO;
use App\Models\Member;
use App\Models\MemberCard;
use App\Repositories\Interfaces\MemberCardRepositoryInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
use Illuminate\Support\Collection;

class MemberCardService implements MemberCardServiceInterface {
    public function __construct(
        protected MemberCardRepositoryInterface $repository
    ) {}

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
        return $this->repository->save($DTO);
    }
}
