<?php
namespace App\Repositories\Eloquent;

use App\DTOs\GetMemberOrderListDTO;
use App\Models\Member;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string|int $id): ?Order
    {
        return Order::where('od_id', '=', $id)->first();
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(Member $member): int
    {
        return Order::where('mb_id', '=', $member->mb_id)
            ->where('od_receipt_ecash', '>', 'O')
            ->sum('od_receipt_ecash');
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(Member $member): int
    {
        return Order::where('mb_id', '=', $member->mb_id)
            ->where('od_receipt_ecash', '>', 'O')
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getList(GetMemberOrderListDTO $DTO)
    {
        $query = Order::where('mb_id', '=', $DTO->member->mb_id)
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
