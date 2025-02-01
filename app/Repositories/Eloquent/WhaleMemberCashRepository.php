<?php
namespace App\Repositories\Eloquent;

use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\WhaleMemberCash;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;

class WhaleMemberCashRepository extends BaseRepository implements MemberCashRepositoryInterface
{
    public function __construct(WhaleMemberCash $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function save(MemberInterface $member): CashInterface
    {
        $cash = new WhaleMemberCash();
        $cash->member_id = $member->getMemberId();
        $cash->amount = 0;
        $cash->save();
        return $cash;
    }

    /**
     * @inheritDoc
     */
    public function lock(MemberInterface $member): ?CashInterface
    {
        return WhaleMemberCash::where('member_id', '=', $member->getMemberId())
            ->lockForUpdate()
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function charge(CashInterface $cash, int $amount): CashInterface
    {
        $cash->setAmount($cash->getAmount() + $amount);
        $cash->save();
        return $cash;
    }

    /**
     * @inheritDoc
     */
    public function spend(CashInterface $cash, int $amount): CashInterface
    {
        $cash->setAmount($cash->getAmount() - $amount);
        $cash->save();
        return $cash;
    }
}
