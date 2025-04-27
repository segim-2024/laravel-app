<?php
namespace App\Repositories\Interfaces;

interface OrderSegimTicketPlusLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $cartId
     * @return bool
     */
    public function exists(string $cartId): bool;
}
