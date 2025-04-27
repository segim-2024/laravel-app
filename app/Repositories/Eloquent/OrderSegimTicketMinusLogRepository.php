<?php
namespace App\Repositories\Eloquent;

use App\Models\OrderSegimTicketMinusLog;
use App\Repositories\Interfaces\OrderSegimTicketMinusLogRepositoryInterface;

class OrderSegimTicketMinusLogRepository extends BaseRepository implements OrderSegimTicketMinusLogRepositoryInterface
{
    public function __construct(OrderSegimTicketMinusLog $model)
    {
        parent::__construct($model);
    }
}
