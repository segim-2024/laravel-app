<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateLibraryProductDTO;
use App\DTOs\UpdateLibraryProductDTO;
use App\DTOs\UpdateLibraryProductIsHidedDTO;
use App\Exceptions\LibraryProductNotFoundException;
use App\Models\LibraryProduct;
use Illuminate\Support\Collection;

interface LibraryProductServiceInterface
{
    /**
     * @param int $productId
     * @return LibraryProduct|null
     */
    public function find(int $productId): ?LibraryProduct;

    /**
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * @param CreateLibraryProductDTO $DTO
     * @return LibraryProduct
     */
    public function create(CreateLibraryProductDTO $DTO): LibraryProduct;

    /**
     * @param UpdateLibraryProductDTO $DTO
     * @return LibraryProduct
     * @throws LibraryProductNotFoundException
     */
    public function update(UpdateLibraryProductDTO $DTO): LibraryProduct;

    /**
     * @return Collection
     */
    public function getListForMember(): Collection;

    /**
     * @param UpdateLibraryProductIsHidedDTO $DTO
     * @return LibraryProduct
     * @throws LibraryProductNotFoundException
     */
    public function updateIsHided(UpdateLibraryProductIsHidedDTO $DTO): LibraryProduct;
}
