<?php
namespace App\Repositories\Eloquent;

use App\Models\Interfaces\ProductInterface;
use App\Models\WhaleMemberSubscribeProduct;
use App\Models\WhaleProduct;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class WhaleProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(WhaleProduct $model)
    {
        parent::__construct($model);
    }

    public function find(int|string $id): ?ProductInterface
    {
        return WhaleProduct::find($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        return WhaleProduct::with([
                'subscribe' => [
                    'card'
                ]
            ])
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getSubscribes(ProductInterface $product): Collection
    {
        return WhaleMemberSubscribeProduct::where('product_id', '=', $product->getId())
            ->where('is_activated','=',true)
            ->where('is_started','=',true)
            ->get();
    }
}