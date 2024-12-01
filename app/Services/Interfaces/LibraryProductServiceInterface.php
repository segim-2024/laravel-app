<?php

namespace App\Services\Interfaces;

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
