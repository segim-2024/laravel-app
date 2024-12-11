<?php

namespace App\Services\Interfaces;

use App\DTOs\LibraryPaymentDoneApiRequestDTO;
use App\DTOs\LibraryPaymentDoneApiResponseDTO;
use JsonException;

interface LibraryApiServiceInterface
{
    /**
     * @param LibraryPaymentDoneApiRequestDTO $DTO
     * @return LibraryPaymentDoneApiResponseDTO
     * @throws JsonException
     */
    public function paymentDone(LibraryPaymentDoneApiRequestDTO $DTO): LibraryPaymentDoneApiResponseDTO;
}
