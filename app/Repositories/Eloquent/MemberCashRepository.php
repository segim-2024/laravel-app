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
}
