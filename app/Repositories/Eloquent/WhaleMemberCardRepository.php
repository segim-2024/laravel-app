<?php

namespace App\Repositories\Eloquent;

use App\DTOs\GetMemberCardApiDTO;
use App\DTOs\MemberCardDTO;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\WhaleMemberCard;
use App\Repositories\Interfaces\MemberCardRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WhaleMemberCardRepository extends BaseRepository implements MemberCardRepositoryInterface
{
    public function __construct(WhaleMemberCard $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(MemberInterface $member, int|string $id): ?CardInterface
    {
        return WhaleMemberCard::where('member_id', '=', $member->getMemberId())->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(MemberInterface $member): Collection
    {
        return WhaleMemberCard::where('member_id', '=', $member->getMemberId())->get();
    }

    /**
     * @inheritDoc
     */
    public function save(MemberCardDTO $DTO): CardInterface
    {
        $card = new WhaleMemberCard();
        $card->member_id = $DTO->member->getMemberId();
        $card->name = $DTO->name;
        $card->number = $DTO->number;
        $card->key = $DTO->key;
        $card->save();
        return $card;
    }

    /**
     * @inheritDoc
     */
    public function getAllMemberCardPaginate(GetMemberCardApiDTO $DTO): LengthAwarePaginator
    {
        $orderOptions = $DTO->getOrderOptions();

        return WhaleMemberCard::when($DTO->search, static function ($query, $search) {
                $query->where('member_id', 'like', "%{$search}%");
            })
            ->orderBy($orderOptions['column'], $orderOptions['direction'])
            ->paginate($DTO->perPage, ['*'], 'page', $DTO->page)
            ->withQueryString();
    }
}