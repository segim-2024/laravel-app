<?php
namespace App\Repositories\Interfaces;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Models\LibraryProductSubscribe;

interface LibraryProductSubscribeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $memberId
     * @return bool
     */
    public function isExistsMemberSubscribe(string $memberId): bool;

    /**
     * @param string $memberId
     * @return LibraryProductSubscribe|null
     */
    public function findByMemberId(string $memberId): ?LibraryProductSubscribe;

    /**
     * @param int $productId
     * @return LibraryProductSubscribe|null
     */
    public function findByProductId(int $productId): ?LibraryProductSubscribe;
}
