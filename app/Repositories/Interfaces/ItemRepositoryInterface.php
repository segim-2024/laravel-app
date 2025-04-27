<?php
namespace App\Repositories\Interfaces;

use App\Models\Item;

interface ItemRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $itemId
     * @return Item|null
     */
    public function find(string $itemId): ?Item;
}
