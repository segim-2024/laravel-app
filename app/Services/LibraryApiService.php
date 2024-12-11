<?php

namespace App\Services;

use App\DTOs\LibraryPaymentDoneApiRequestDTO;
use App\DTOs\LibraryPaymentDoneApiResponseDTO;
use App\Services\Interfaces\LibraryApiServiceInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LibraryApiService implements LibraryApiServiceInterface {
    /**
     * @inheritDoc
     */
    public function paymentDone(LibraryPaymentDoneApiRequestDTO $DTO): LibraryPaymentDoneApiResponseDTO
    {
        try {
            $response = Http::library()
                ->post("/campus/autoSaveElibrary", $DTO->toPayload());
        } catch (Exception $e) {
            Log::error($e);
            return new LibraryPaymentDoneApiResponseDTO(
                httpStatusCode: Response::HTTP_BAD_GATEWAY,
                isSuccess: false,
                message: $e->getMessage(),
            );
        }

        return LibraryPaymentDoneApiResponseDTO::createFromPayloadAndResponse($DTO->toPayload(), $response);
    }
}
