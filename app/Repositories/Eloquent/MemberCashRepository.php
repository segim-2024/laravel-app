<?php
namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Models\MemberCash;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;

class MemberCashRepository extends BaseRepository implements MemberCashRepositoryInterface
{
    public function __construct(MemberCash $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function save(Member $member): MemberCash
    {
        $cash = new MemberCash();
        $cash->member_id = $member->mb_id;
        $cash->save();
        return $cash;
    }

    /**
     * @inheritDoc
     */
    public function lock(Member $member): MemberCash
    {
        return MemberCash::where('member_id', '=', $member->mb_id)->lockForUpdate()->first();
    }

    /**
     * @inheritDoc
     */
    public function charge(MemberCash $cash, int $amount): MemberCash
    {
        $cash->amount += $amount;
        $cash->save();
        return $cash;
    }

    /**
     * @inheritDoc
     */
    public function spend(MemberCash $cash, int $amount): MemberCash
    {
        $cash->amount -= $amount;
        $cash->save();
        return $cash;
    }

    /**
     * @inheritDoc
     */
    public function canSpendCheck(Member $member, int $amount): bool
    {
        return $member->cash->amount >= $amount;
    }
}
