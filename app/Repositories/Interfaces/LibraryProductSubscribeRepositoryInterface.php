<?php
namespace App\Repositories\Interfaces;

use App\Models\LibraryProductSubscribe;
use Illuminate\Support\Collection;

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
     * @param string $memberId
     * @param int $productId
     * @return LibraryProductSubscribe|null
     */
    public function findByMemberIdAndProductId(string $memberId, int $productId): ?LibraryProductSubscribe;

    /**
     * @return Collection
     */
    public function getSubscriptionsDueToday(): Collection;
}
