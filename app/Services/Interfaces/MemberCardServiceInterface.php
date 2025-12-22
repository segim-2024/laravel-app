<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberCardDTO;
use App\DTOs\GetMemberCardApiDTO;
use App\Exceptions\PortOneGetBillingKeyException;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MemberCardServiceInterface
{
    /**
     * @param int $id
     * @return CardInterface|null
     */
    public function findById(int $id): ?CardInterface;

    /**
     * @param MemberInterface $member
     * @param string|int $id
     * @return CardInterface|null
     */
    public function find(MemberInterface $member, string|int $id): ?CardInterface;

    /**
     * @param GetMemberCardApiDTO $DTO
     * @return LengthAwarePaginator
     */
    public function getAllMemberCards(GetMemberCardApiDTO $DTO): LengthAwarePaginator;

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
     * @param CreateMemberCardDTO $DTO
     * @return CardInterface
     * @throws PortOneGetBillingKeyException
     */
    public function save(CreateMemberCardDTO $DTO): CardInterface;

    /**
     * @param CardInterface $card
     * @return void
     */
    public function delete(CardInterface $card): void;
}
