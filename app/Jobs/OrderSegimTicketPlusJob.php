<?php

namespace App\Jobs;

use App\DTOs\CreateOrderSegimTicketPlusLogDTO;
use App\DTOs\SegimTicketApiDTO;
use App\Enums\SegimTicketTypeEnum;
use App\Exceptions\SegimTicketApiErrorException;
use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderSegimTicketServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderSegimTicketPlusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $ctId
    )
    {
        //
    }

    /**
     * Execute the job.
     * @throws SegimTicketApiErrorException
     */
    public function handle(
        OrderSegimTicketServiceInterface $service,
        CartServiceInterface $cartService
    ): void
    {
        $isExists = $service->isExistsPlusLog($this->ctId);
        if ($isExists) {
            Log::info("Already exists plus log: {$this->ctId}");
            return;
        }

        $cart = $cartService->find($this->ctId);
        if (! $cart) {
            Log::warning("Cart not found: {$this->ctId}");
            return;
        }

        if ($cart->item && $cart->item->segim_ticket_type) {
            $ticketType = SegimTicketTypeEnum::from($cart->item->segim_ticket_type);

            $DTO = new SegimTicketApiDTO(
                mbNo: $cart->member->mb_no,
                qty: $cart->ct_qty,
                ticketType: $ticketType,
                description: "프로모션 티켓 발급"
            );

            $apiResponseDTO = $service->callApi($DTO);

            if (! $apiResponseDTO->isSuccess) {
                Log::error("Failed to call API: {$this->ctId}", [
                    'error' => $apiResponseDTO->httpStatusCode,
                    'message' => $apiResponseDTO->message,
                    'response' => $apiResponseDTO->data
                ]);

                throw new SegimTicketApiErrorException("API call failed: {$this->ctId}");
            }

            $DTO = new CreateOrderSegimTicketPlusLogDTO(
                ctId: $this->ctId,
                ticketType: $ticketType,
                api: $apiResponseDTO->data
            );

            $service->createPlusLog($DTO);
        }
    }
}
