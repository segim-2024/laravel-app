<?php
namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return Product::with([
                'subscribe' => [
                    'card'
                ]
            ])
            ->get();
    }
}
