<?php
namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MemberRepository extends BaseRepository implements MemberRepositoryInterface
{
    public function __construct(Member $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $id): ?Member
    {
        return Member::where('mb_id', '=', $id)->first();
    }

    /**
     * @inheritDoc
     */
    public function findFromWhale(string $id): ?WhaleMember
    {
        return WhaleMember::where('mb_id', '=', $id)->first();
    }

    /**
     * @inheritDoc
     */
    public function updateTossCustomerKey(Member $member): Member
    {
        $member->toss_customer_key = Str::orderedUuid();
        $member->save();
        return $member;
    }
}
