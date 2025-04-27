<?php
namespace App\Repositories\Eloquent;

use App\Models\OrderSegimTicketPlusLog;
use App\Repositories\Interfaces\OrderSegimTicketPlusLogRepositoryInterface;

class OrderSegimTicketPlusLogPlusLogRepository extends BaseRepository implements OrderSegimTicketPlusLogRepositoryInterface
{
    public function __construct(OrderSegimTicketPlusLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $cartId): bool
    {
        return OrderSegimTicketPlusLog::where('ct_id', $cartId)->exists();
    }
}
