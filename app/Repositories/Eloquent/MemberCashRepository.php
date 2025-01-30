<?php
namespace App\Repositories\Eloquent;

use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\MemberCash;
use App\Models\WhaleMember;
use App\Models\WhaleMemberCash;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;
use RuntimeException;

class MemberCashRepository extends BaseRepository implements MemberCashRepositoryInterface
{
    public function __construct(MemberCash $model)
    {
        parent::__construct($model);
    }

    private function resolveCashModel(MemberInterface $member): CashInterface
    {
        if ($member instanceof Member) {
            return new MemberCash();
        }

        if ($member instanceof WhaleMember) {
            return new WhaleMemberCash();
        }

        throw new RuntimeException('Invalid member type');
    }

    /**
     * @inheritDoc
     */
    public function save(MemberInterface $member): CashInterface
    {
        $cash = $this->resolveCashModel($member);
        $cash->member_id = $member->getMemberId();
        $cash->save();
        return $cash;
    }

    /**
     * @inheritDoc
     */
    public function lock(MemberInterface $member): CashInterface
    {
        $model = $this->resolveCashModel($member);
        return $model::where('member_id', '=', $member->getMemberId())
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

    /**
     * @inheritDoc
     */
    public function canSpendCheck(MemberInterface $member, int $amount): bool
    {
        $cash = $member->getCash();
        if (! $cash) {
            return false;
        }

        return $cash->getAmount() >= $amount;
    }
}
