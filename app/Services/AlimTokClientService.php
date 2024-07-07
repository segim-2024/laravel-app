<?php

namespace App\Services;

use App\DTOs\AlimTokDTO;
use App\DTOs\AlimTokResponseDTO;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AlimTokClientService implements AlimTokClientServiceInterface {

    /**
     * @inheritDoc
     */
    public function send(AlimTokDTO $DTO): AlimTokResponseDTO
    {
        $params = [
            'auth_code' => Config::get('services.mts.auth_code'),
            'sender_key' => Config::get('services.mts.sender_key'),
            'callback_number' => "1670-1705",
            'phone_number' => $DTO->phoneNumber,
            'template_code' => $DTO->templateCode,
            'message' => $DTO->message,
//            'tran_type' => 'Y',
//            'tran_message' => 'tran_message',
        ];

        if ($DTO->title) {
            $params['title'] = $DTO->title;
        }

        if ($DTO->header) {
            $params['header'] = $DTO->header;
        }

        if ($DTO->attachment) {
            $params['attachment'] = $DTO->attachment;
        }

        $response = Http::mts()
            ->post("/sndng/atk/sendMessage", $params);

        if ($response->failed()) {
            throw new RuntimeException($response->body(), $response->status());
        }

        return AlimTokResponseDTO::createFromResponse($response);
    }
}
