<?php

namespace App\Services\Interfaces;

use App\DTOs\PamusPointApiResponseDTO;

interface PamusPointApiServiceInterface
{
    public function convert(
        string $mbId,
        int $amount,
        string $uniqueIdx,
        ?string $content = null
    ): PamusPointApiResponseDTO;
}