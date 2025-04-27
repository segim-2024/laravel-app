<?php
namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $itemId): ?Item
    {
        return Item::where('it_id', $itemId)->first();
    }
}
