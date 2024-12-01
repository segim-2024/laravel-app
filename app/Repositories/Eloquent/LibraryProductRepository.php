<?php
namespace App\Repositories\Eloquent;

use App\DTOs\UpdateLibraryProductIsHidedDTO;
use App\Models\LibraryProduct;
use App\Repositories\Interfaces\LibraryProductRepositoryInterface;
use Illuminate\Support\Collection;

class LibraryProductRepository extends BaseRepository implements LibraryProductRepositoryInterface
{
    public function __construct(LibraryProduct $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        return LibraryProduct::all();
    }

    public function getListWithSubscribeForMember(): Collection
    {
        return LibraryProduct::with([
                'subscribe' => [
                    'card'
                ]
            ])
            ->get();
    }
}
