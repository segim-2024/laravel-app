<?php

namespace App\Services\Interfaces;

use App\DTOs\OrderDTO;

interface OrderAlimTokServiceInterface
{
    /**
     * @param OrderDTO $DTO
     * @return void
     */
    public function payment(OrderDTO $DTO): void;

    /**
     * @param OrderDTO $DTO
     * @return void
     */
    public function depositGuidance(OrderDTO $DTO): void;

    /**
     * @param OrderDTO $DTO
     * @return void
     */
    public function shipmentTrack(OrderDTO $DTO): void;
}
