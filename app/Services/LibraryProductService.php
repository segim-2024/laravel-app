<?php

namespace App\Services;

use App\DTOs\UpdateLibraryProductIsHidedDTO;
use App\Exceptions\LibraryProductNotFoundException;
use App\Models\LibraryProduct;
use App\Repositories\Interfaces\LibraryProductRepositoryInterface;
use App\Services\Interfaces\LibraryProductServiceInterface;
use Illuminate\Support\Collection;

class LibraryProductService implements LibraryProductServiceInterface {
    public function __construct(
        protected LibraryProductRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(int $productId): ?LibraryProduct
    {
        return $this->repository->findById($productId);
    }

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        return $this->repository->getList();
    }

    /**
     * @inheritDoc
     */
    public function getListForMember(): Collection
    {
        return $this->repository->getListWithSubscribeForMember();
    }

    /**
     * @inheritDoc
     */
    public function updateIsHided(UpdateLibraryProductIsHidedDTO $DTO): LibraryProduct
    {
        $product = $this->find($DTO->id);
        if (! $product) {
            throw new LibraryProductNotFoundException('상품을 찾을 수 없습니다.');
        }

        return $this->repository->update($product, $DTO->toModelAttribute());
    }
}
