<?php
namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Models\MemberSubscribeProduct;
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

    public function find(int|string $id): ?Product
    {
        return Product::find($id);
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

    /**
     * @inheritDoc
     */
    public function getSubscribes(Product $product): Collection
    {
        return MemberSubscribeProduct::where('product_id', '=', $product->id)
            ->where('is_activated','=',true)
            ->where('is_started','=',true)
            ->get();
    }
}
