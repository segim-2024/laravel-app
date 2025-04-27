<?php

namespace App\Jobs;

use App\DTOs\CreateOrderSegimTicketMinusLogDTO;
use App\DTOs\SegimTicketApiDTO;
use App\Enums\SegimTicketMinusTypeEnum;
use App\Enums\SegimTicketTypeEnum;
use App\Exceptions\SegimTicketApiErrorException;
use App\Models\Cart;
use App\Models\ReturnItem;
use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderSegimTicketServiceInterface;
use App\Services\Interfaces\ReturnItemServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderSegimTicketMinusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected SegimTicketMinusTypeEnum $type,
        protected string $id,
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
        ReturnItemServiceInterface $returnItemService,
        CartServiceInterface $cartService
    ): void
    {
        if ($this->type->isReturn()) {
            $item = $returnItemService->find($this->id);
            $memberId = $item?->member->mb_id;
            $memberNo = $item?->member->mb_no;
            $qty = $item?->qty;
            $itemId = $item?->cart->it_id;
            $ticketType = $item?->cart->item->segim_ticket_type;
        } else {
            $item = $cartService->find($this->id);
            $memberId = $item?->member->mb_id;
            $memberNo = $item?->member->mb_no;
            $qty = $item?->ct_qty;
            $itemId = $item?->it_id;
            $ticketType = $item?->item->segim_ticket_type;
        }

        $ticketType = SegimTicketTypeEnum::from($ticketType);

        if (! $item) {
            Log::warning("Item not found: {$this->type->value} {$this->id}");
            return;
        }

        if ($item->ticketMinusLog) {
            return;
        }

        if ($item instanceof Cart && ! $item->item->segim_ticket_type) {
            Log::warning("Segim ticket type is null: {$this->type->value} {$this->id}");
            return;
        }

        if ($item instanceof ReturnItem && ! $item->cart->item->segim_ticket_type) {
            Log::warning("Segim ticket type is null: {$this->type->value} {$this->id}");
            return;
        }

        $DTO = new SegimTicketApiDTO(
            mbNo: $memberNo,
            qty: $qty * -1,
            ticketType: $ticketType,
            description: "프로모션 티켓 차감"
        );

        $apiResponseDTO = $service->callApi($DTO);

        if (! $apiResponseDTO->isSuccess) {
            Log::error("Failed to call API: {$this->type->value} {$this->id}", [
                'error' => $apiResponseDTO->httpStatusCode,
                'message' => $apiResponseDTO->message,
                'response' => $apiResponseDTO->data
            ]);

            throw new SegimTicketApiErrorException("API call failed: {$this->type->value} {$this->id}");
        }

        $DTO = new CreateOrderSegimTicketMinusLogDTO(
            memberId: $memberId,
            itemId: $itemId,
            orderProduct: $item,
            ticketType: $ticketType,
            qty: $qty * -1,
            api: $apiResponseDTO->data
        );

        $service->createMinusLog($DTO);
    }
}
