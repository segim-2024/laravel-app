<?php

namespace App\Services;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;

class MemberPaymentService implements MemberPaymentServiceInterface {
    public function __construct(
        protected MemberPaymentRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function getList(GetMemberPaymentListDTO $DTO)
    {
        return $this->repository->getList($DTO);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(Member $member): int
    {
        return $this->repository->getTotalAmount($member);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(Member $member): int
    {
        return $this->repository->getTotalPaymentCount($member);
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberPaymentDTO $DTO): MemberPayment
    {
        return $this->repository->save($DTO);
    }

    /**
     * @inheritDoc
     */
    public function process(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        return match ($DTO->status) {
            'DONE' => $this->processDone($payment, $DTO),
            'CANCELED', 'PARTIAL_CANCELED' => $this->processCancelled($payment, $DTO),
            'ABORTED' => $this->processAborted($payment, $DTO),
        };
    }

    private function processDone(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {


        return $this->repository->updateDone($payment, $DTO);
    }

    private function processCancelled(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        return $this->repository->updateDone($payment, $DTO);
    }

    private function processAborted(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        return $this->repository->updateDone($payment, $DTO);
    }
}
