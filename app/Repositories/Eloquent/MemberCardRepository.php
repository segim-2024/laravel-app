<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateMemberCardDTO;
use App\Models\Member;
use App\Models\MemberCard;
use App\Repositories\Interfaces\MemberCardRepositoryInterface;
use Illuminate\Support\Collection;

class MemberCardRepository extends BaseRepository implements MemberCardRepositoryInterface
{
    public function __construct(MemberCard $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(Member $member, int|string $id): ?MemberCard
    {
        return MemberCard::where('member_id', '=', $member->mb_id)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return MemberCard::where('member_id', '=', $member->mb_id)->get();
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberCardDTO $DTO): MemberCard
    {
        $card = new MemberCard();
        $card->member_id = $DTO->member->mb_id;
        $card->name = $DTO->name;
        $card->number = $DTO->number;
        $card->key = $DTO->key;
        $card->save();
        return $card;
    }
}
