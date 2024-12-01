<?php

namespace App\Services;

use App\DTOs\GetLibraryPaymentListDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\DTOs\UpdateLibraryProductSubscribeDatesDTO;
use App\DTOs\UpdateLibraryProductSubscribeStateToUnpaidDTO;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\LibraryPaymentRepositoryInterface;
use App\Services\Interfaces\LibraryPaymentServiceInterface;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;

class LibraryPaymentService implements LibraryPaymentServiceInterface {
    public function __construct(
        protected LibraryProductSubscribeServiceInterface $subscribeService,
        protected LibraryPaymentRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function getList(GetLibraryPaymentListDTO $DTO)
    {
        return $this->repository->getList($DTO);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(string $memberId): int
    {
        return $this->repository->getTotalAmount($memberId);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(string $memberId): int
    {
        return $this->repository->getTotalPaymentCount($memberId);
    }

    /**
     * @inheritDoc
     */
    public function processPaid(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): void
    {
        $this->subscribeService->updateDatesOnSuccess(
            UpdateLibraryProductSubscribeDatesDTO::createFromPayment($payment)
        );

        // TODO : 라이브러리 API 요청 Job 디스패치

    }

    /**
     * @inheritDoc
     */
    public function processFailed(MemberPayment $payment): void
    {
        $this->subscribeService->updateStateToUnpaid(
            UpdateLibraryProductSubscribeStateToUnpaidDTO::createFromPayment($payment)
        );

        if ($payment->member->mb_hp) {
            // TODO : 미납 알림톡 발송 Job 디스패치
        }
    }
}
