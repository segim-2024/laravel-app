<?php

namespace App\Services\Interfaces;

use App\DTOs\AlimTokResponseDTO;
use App\DTOs\ShipmentTrackDTO;

interface OrderAlimTokServiceInterface
{
    /**
     * @param ShipmentTrackDTO $DTO
     * @return AlimTokResponseDTO
     */
    public function shipmentTrack(ShipmentTrackDTO $DTO): AlimTokResponseDTO;
}
