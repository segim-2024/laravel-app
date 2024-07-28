<?php

namespace App\Services;

use App\DTOs\ShipmentTrackDTO;
use App\Jobs\SendShipmentTrackTokJob;
use App\Services\Interfaces\OrderAlimTokServiceInterface;

class OrderAlimTokService implements OrderAlimTokServiceInterface {

    /**
     * @inheritDoc
     */
    public function shipmentTrack(ShipmentTrackDTO $DTO): void
    {
        SendShipmentTrackTokJob::dispatch($DTO);
    }
}
