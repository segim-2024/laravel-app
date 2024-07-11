<?php

namespace App\Services;

use App\DTOs\AlimTokDTO;
use App\DTOs\AlimTokResponseDTO;
use App\DTOs\ShipmentTrackDTO;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use App\Services\Interfaces\OrderAlimTokServiceInterface;
use Carbon\Carbon;

class OrderAlimTokService implements OrderAlimTokServiceInterface {
    public function __construct(
        protected AlimTokClientServiceInterface $alimTokClientService
    ) {}

    /**
     * @inheritDoc
     */
    public function shipmentTrack(ShipmentTrackDTO $DTO): AlimTokResponseDTO
    {
        $phone = str_replace('-', '', $DTO->order->member->mb_hp);
        $content = view('toks.shipment_track', [
            'memberSchoolName' => $DTO->order->member->mb_nick,
            'orderDate' => Carbon::parse($DTO->order->od_time)->format('Y-m-d H:i:s'),
            'orderNo' => $DTO->order->od_id
        ])->render();
        $link = "https://trace.cjlogistics.com/web/detail.jsp?slipno={$DTO->order->od_invoice}";
        $attachments = [
            'button' => [
                'name' => "배송 조회",
                'type' => "WL",
                'url_pc' => $link,
                'url_mobile' => $link,
                'target' => "out",
            ]
        ];

        return $this->alimTokClientService->send(new AlimTokDTO(
            phoneNumber: $phone,
            templateCode: 'deliver01_2',
            message: $content,
            attachments: $attachments
        ));
    }
}
