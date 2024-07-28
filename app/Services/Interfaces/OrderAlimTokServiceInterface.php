<?php

namespace App\Services\Interfaces;

use App\DTOs\ShipmentTrackDTO;

interface OrderAlimTokServiceInterface
{
    /**
     * @param ShipmentTrackDTO $DTO
     * @return void
     */
    public function shipmentTrack(ShipmentTrackDTO $DTO): void;
}
