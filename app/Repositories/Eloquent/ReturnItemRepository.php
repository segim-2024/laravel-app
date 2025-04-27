<?php
namespace App\Repositories\Eloquent;

use App\Models\ReturnItem;
use App\Repositories\Interfaces\ReturnItemRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ReturnItemRepository extends BaseRepository implements ReturnItemRepositoryInterface
{
    public function __construct(ReturnItem $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $rfiId): ?ReturnItem
    {
        return ReturnItem::where('rfi_id', $rfiId)
            ->with(['cart' => ['item'], 'member'])
            ->first();
    }
}
