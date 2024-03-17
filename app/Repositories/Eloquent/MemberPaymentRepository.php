<?php
namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use Illuminate\Support\Collection;

class MemberPaymentRepository extends BaseRepository implements MemberPaymentRepositoryInterface
{
    public function __construct(MemberPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return MemberPayment::with('card')
            ->where('member_id', '=', $member->mb_id)
            ->get();
    }
}
