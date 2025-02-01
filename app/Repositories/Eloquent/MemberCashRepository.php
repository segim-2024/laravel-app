<?php
namespace App\Repositories\Eloquent;

use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;
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
    public function save(MemberInterface $member): CashInterface
    {
        $cash = new MemberCash();
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
        return MemberCash::where('member_id', '=', $member->getMemberId())
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
