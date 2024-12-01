<?php
namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface LibraryProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 상품 리스트
     *
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * 멤버를 위한 리스트
     *
     * @return Collection
     */
    public function getListWithSubscribeForMember(): Collection;
}
