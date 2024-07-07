<?php

namespace App\Services\Interfaces;

use App\DTOs\AlimTokDTO;
use App\DTOs\AlimTokResponseDTO;

interface AlimTokClientServiceInterface
{
    /**
     * @param AlimTokDTO $DTO
     * @return AlimTokResponseDTO
     */
    public function send(AlimTokDTO $DTO): AlimTokResponseDTO;
}
