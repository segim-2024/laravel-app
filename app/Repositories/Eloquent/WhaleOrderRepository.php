<?php

namespace App\Repositories\Eloquent;

use App\DTOs\GetMemberOrderListDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\OrderInterface;
use App\Models\Order;
use App\Models\WhaleOrder;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class WhaleOrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(WhaleOrder $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string|int $id): ?Order
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function findWithPlatform(int|string $id, bool $isWhale = false): ?OrderInterface
    {
        return WhaleOrder::where('od_id', '=', $id)->first();
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(MemberInterface $member): int
    {
        return WhaleOrder::where('mb_id', '=', $member->getMemberId())
            ->where('od_receipt_ecash', '>', 'O')
            ->sum('od_receipt_ecash');
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(MemberInterface $member): int
    {
        return WhaleOrder::where('mb_id', '=', $member->getMemberId())
            ->where('od_receipt_ecash', '>', 'O')
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getList(GetMemberOrderListDTO $DTO)
    {
        $query = WhaleOrder::where('mb_id', '=', $DTO->member->getMemberId())
            ->where('od_receipt_ecash', '>', 'O')
            ->with([
                'member',
                'carts'
            ])
            ->when($DTO->keyword, function ($query, $keyword) {
                return $query->whereHas('carts', function ($query) use ($keyword) {
                    $query->where('it_name', 'LIKE', "%{$keyword}%");
                });
            })
            ->when($DTO->start, fn($query) => $query->where('od_time', '>=', $DTO->start))
            ->when($DTO->end, fn($query) => $query->where('od_time', '<=', $DTO->end));

        return DataTables::eloquent($query)->make();
    }
}