<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateOrderSegimTicketMinusLogDTO;
use App\DTOs\CreateOrderSegimTicketPlusLogDTO;
use App\DTOs\OrderSegimTicketMinusDTO;
use App\DTOs\OrderSegimTicketPlusDTO;
use App\DTOs\SegimTicketApiDTO;
use App\DTOs\SegimTicketApiResponseDTO;

interface OrderSegimTicketServiceInterface
{
    /**
     * @param string $ctId
     * @return bool
     */
    public function isExistsPlusLog(string $ctId): bool;

    /**
     * @param CreateOrderSegimTicketPlusLogDTO $DTO
     * @return void
     */
    public function createPlusLog(CreateOrderSegimTicketPlusLogDTO $DTO): void;

    /**
     * @param OrderSegimTicketPlusDTO $DTO
     * @return void
     */
    public function callPlusJob(OrderSegimTicketPlusDTO $DTO): void;

    /**
     * @param CreateOrderSegimTicketMinusLogDTO $DTO
     * @return void
     */
    public function createMinusLog(CreateOrderSegimTicketMinusLogDTO $DTO): void;

    /**
     * @param OrderSegimTicketMinusDTO $DTO
     * @return void
     */
    public function callMinusJob(OrderSegimTicketMinusDTO $DTO): void;

    /**
     * @param SegimTicketApiDTO $DTO
     * @return SegimTicketApiResponseDTO
     */
    public function callApi(SegimTicketApiDTO $DTO): SegimTicketApiResponseDTO;
}
