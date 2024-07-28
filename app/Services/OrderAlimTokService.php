<?php

namespace App\Services;

use App\DTOs\OrderDTO;
use App\Jobs\SendDepositGuidanceAlimTokJob;
use App\Jobs\SendOrderPaymentAlimTokJob;
use App\Jobs\SendShipmentTrackTokJob;
use App\Services\Interfaces\OrderAlimTokServiceInterface;

class OrderAlimTokService implements OrderAlimTokServiceInterface {
    /**
     * @inheritDoc
     */
    public function payment(OrderDTO $DTO): void
    {
        SendOrderPaymentAlimTokJob::dispatch($DTO);
    }

    /**
     * @inheritDoc
     */
    public function depositGuidance(OrderDTO $DTO): void
    {
        SendDepositGuidanceAlimTokJob::dispatch($DTO);
    }

    /**
     * @inheritDoc
     */
    public function shipmentTrack(OrderDTO $DTO): void
    {
        SendShipmentTrackTokJob::dispatch($DTO);
    }
}
