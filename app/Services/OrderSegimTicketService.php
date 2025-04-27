<?php

namespace App\Services;

use App\DTOs\CreateOrderSegimTicketMinusLogDTO;
use App\DTOs\CreateOrderSegimTicketPlusLogDTO;
use App\DTOs\OrderSegimTicketMinusDTO;
use App\DTOs\OrderSegimTicketPlusDTO;
use App\DTOs\SegimTicketApiDTO;
use App\DTOs\SegimTicketApiResponseDTO;
use App\Enums\SegimTicketMinusTypeEnum;
use App\Jobs\OrderSegimTicketMinusJob;
use App\Jobs\OrderSegimTicketPlusJob;
use App\Models\Cart;
use App\Models\Interfaces\SegimTicketMinusInterface;
use App\Models\ReturnItem;
use App\Repositories\Interfaces\OrderSegimTicketMinusLogRepositoryInterface;
use App\Repositories\Interfaces\OrderSegimTicketPlusLogRepositoryInterface;
use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderSegimTicketServiceInterface;
use App\Services\Interfaces\ReturnItemServiceInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderSegimTicketService implements OrderSegimTicketServiceInterface {
    public function __construct(
        protected OrderSegimTicketPlusLogRepositoryInterface  $plusLogRepository,
        protected OrderSegimTicketMinusLogRepositoryInterface $minusLogRepository,
        protected CartServiceInterface                        $cartService,
        protected ReturnItemServiceInterface                  $returnItemService,
    ) {}

    /**
     * @inheritDoc
     */
    public function isExistsPlusLog(string $ctId): bool
    {
        return $this->plusLogRepository->exists($ctId);
    }

    /**
     * @inheritDoc
     */
    public function createPlusLog(CreateOrderSegimTicketPlusLogDTO $DTO): void
    {
        $cart = $this->cartService->find($DTO->ctId);
        if (! $cart) {
            Log::warning("Cart not found: {$DTO->ctId}");
            return;
        }

        $this->plusLogRepository->create([
            'mb_id' => $cart->mb_id,
            'od_id'   => $cart->od_id,
            'it_id' => $cart->it_id,
            'ct_id' => $cart->ct_id,
            'ct_qty' => $cart->ct_qty,
            'ticket_type' => $DTO->ticketType,
            'api' => $DTO->api
        ]);
    }

    /**
     * @inheritDoc
     */
    public function callPlusJob(OrderSegimTicketPlusDTO $DTO): void
    {
        $DTO->ctIds->each(function (string $ctId) {
            $isExists = $this->isExistsPlusLog($ctId);
            if ($isExists) {
                return;
            }

            $cart = $this->cartService->find($ctId);
            if (! $cart) {
                Log::warning("Cart not found: $ctId");
                return;
            }

            if (! $cart->item->segim_ticket_type) {
                return;
            }

            OrderSegimTicketPlusJob::dispatch($ctId);
        });
    }

    /**
     * @inheritDoc
     */
    public function createMinusLog(CreateOrderSegimTicketMinusLogDTO $DTO): void
    {
        $this->minusLogRepository->create([
            'mb_id' => $DTO->memberId,
            'cartable_type' => get_class($DTO->orderProduct),
            'cartable_id' => $DTO->orderProduct->getKey(),
            'qty' => $DTO->qty,
            'it_id' => $DTO->itemId,
            'ticket_type' => $DTO->ticketType,
            'api' => $DTO->api
        ]);
    }

    /**
     * @inheritDoc
     */
    public function callMinusJob(OrderSegimTicketMinusDTO $DTO): void
    {
        $DTO->ids->each(function (string $id) use ($DTO) {
            if ($DTO->type->isReturn()) {
                $item = $this->returnItemService->find($id);
            } else {
                $item = $this->cartService->find($id);
            }

            if (! $item) {
                Log::warning("Item not found: $id");
                return;
            }

            if ($item->ticketMinusLog) {
                return;
            }

            if ($item instanceof Cart && ! $item->item->segim_ticket_type) {
                return;
            }

            if ($item instanceof ReturnItem && ! $item->cart->item->segim_ticket_type) {
                return;
            }

            OrderSegimTicketMinusJob::dispatch($DTO->type, $id);
        });
    }

    /**
     * @inheritDoc
     */
    public function callApi(SegimTicketApiDTO $DTO): SegimTicketApiResponseDTO
    {
        try {
            $response = Http::segim()
                ->post("/ticket/external", $DTO->toPayload());
        } catch (Exception $e) {
            Log::error($e);
            return new SegimTicketApiResponseDTO(
                httpStatusCode: Response::HTTP_BAD_GATEWAY,
                isSuccess: false,
                message: $e->getMessage(),
            );
        }

        return SegimTicketApiResponseDTO::createFromPayloadAndResponse($DTO->toPayload(), $response);
    }
}
