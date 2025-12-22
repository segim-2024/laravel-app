<?php
namespace App\Repositories\Interfaces;

use App\DTOs\GetMemberCardApiDTO;
use App\DTOs\MemberCardDTO;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MemberCardRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberInterface $member
     * @param string|int $id
     * @return CardInterface|null
     */
    public function find(MemberInterface $member, string|int $id): ?CardInterface;

    /**
     * 카드 리스트
     *
     * @param MemberInterface $member
     * @return Collection
     */
    public function getList(MemberInterface $member): Collection;

    /**
     * 카드 생성
     *
     * @param MemberCardDTO $DTO
     * @return CardInterface
     */
    public function save(MemberCardDTO $DTO): CardInterface;

    /**
     * @param GetMemberCardApiDTO $DTO
     */
    public function getAllMemberCardPaginate(GetMemberCardApiDTO $DTO): LengthAwarePaginator;
}
