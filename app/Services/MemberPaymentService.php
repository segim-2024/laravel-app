<?php

namespace App\Services;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Exceptions\PaymentIsNotFailedException;
use App\Models\LibraryProduct;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use App\Models\Product;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use App\Repositories\Interfaces\ProductPaymentRepositoryInterface;
use App\Services\Interfaces\LibraryPaymentServiceInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\PortOneServiceInterface;
use App\Services\Interfaces\ProductPaymentServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MemberPaymentService implements MemberPaymentServiceInterface {
    public function __construct(
        protected PortOneServiceInterface           $portOneService,
        protected ProductPaymentServiceInterface    $productPaymentService,
        protected LibraryPaymentServiceInterface    $libraryPaymentService,
        protected MemberPaymentRepositoryInterface  $repository,
        protected ProductPaymentRepositoryInterface $productPaymentRepository
    ) {}

    /**
     * @inheritDoc
     */
    public function findByKey(string $key): ?MemberPayment
    {
        return $this->repository->findByKey($key);
    }

    /**
     * @inheritDoc
     */
    public function getList(GetMemberPaymentListDTO $DTO)
    {
        return $this->productPaymentRepository->getList($DTO);
    }

    /**
     * @inheritDoc
     */
    public function findFailedPayment(string $paymentId): ?MemberPayment
    {
        return $this->repository->findFailedPayment($paymentId);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(Member $member): int
    {
        return $this->productPaymentRepository->getTotalAmount($member->mb_id);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(Member $member): int
    {
        return $this->productPaymentRepository->getTotalPaymentCount($member->mb_id);
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
    public function retry(PaymentRetryDTO $DTO): MemberPayment
    {
        if ($DTO->payment->card_id !== $DTO->subscribe->card_id) {
            $DTO->payment = $this->updateCard($DTO->payment, $DTO->subscribe->card);
        }

        $this->portOneService->requestPaymentByBillingKey($DTO->subscribe->card->key, $DTO->payment);
        return $DTO->payment;
    }

    /**
     * @inheritDoc
     */
    public function cancel(PaymentCancelDTO $DTO): MemberPayment
    {
        $this->portOneService->cancel($DTO);
        return $DTO->payment;
    }

    /**
     * @inheritDoc
     */
    public function updateCard(MemberPayment $payment, MemberCard $card): MemberPayment
    {
        return $this->repository->updateCard($payment, $card);
    }

    /**
     * @inheritDoc
     */
    public function process(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        return match ($DTO->status) {
            MemberPaymentStatusEnum::Ready, MemberPaymentStatusEnum::Unpaid => $payment,
            MemberPaymentStatusEnum::Paid => $this->processPaid($payment, $DTO),
            MemberPaymentStatusEnum::Cancelled, MemberPaymentStatusEnum::PartialCancelled => $this->processCancelled($payment, $DTO),
            default => $this->processFailed($payment, $DTO),
        };
    }

    /**
     * @throws LibraryProductSubscribeNotFoundException
     */
    private function processPaid(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        if ($payment->state === $DTO->status) {
            return $payment;
        }

        // 상품에 따른 결제 완료 처리
        match (true) {
            $payment->productable instanceOf Product => $this->productPaymentService->processPaid($payment, $DTO),
            $payment->productable instanceOf LibraryProduct => $this->libraryPaymentService->processPaid($payment, $DTO),
            default => null,
        };

        return $this->repository->updateDone($payment, $DTO);
    }

    private function processCancelled(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        if ($payment->state === MemberPaymentStatusEnum::Cancelled) {
            return $payment;
        }

        return $this->repository->updateCanceled($payment, $DTO);
    }

    /**
     * @throws LibraryProductSubscribeNotFoundException
     */
    private function processFailed(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        // 상품에 따른 결제 완료 처리
        match (true) {
            $payment->productable instanceOf Product => $this->productPaymentService->processFailed($payment),
            $payment->productable instanceOf LibraryProduct => $this->libraryPaymentService->processFailed($payment),
            default => null,
        };

        return $this->repository->updateFailed($payment, $DTO);
    }

    /**
     * @inheritDoc
     */
    public function manuallySetFailed(MemberPayment $payment, string $api): MemberPayment
    {
        // 상품에 따른 결제 완료 처리
        match (true) {
            $payment->productable instanceOf Product => $this->productPaymentService->processFailed($payment),
            $payment->productable instanceOf LibraryProduct => $this->libraryPaymentService->processFailed($payment),
            default => null,
        };

        return $this->repository->manuallySetFailed($payment, $api);
    }

    /**
     * @inheritDoc
     */
    public function deleteFailedPayment(string $paymentId): void
    {
        $payment = $this->repository->findByKey($paymentId);
        if (! $payment) {
            throw new ModelNotFoundException("대상 결제 정보를 찾을 수 없습니다.");
        }

        if ($payment->state->isFailed()) {
            throw new PaymentIsNotFailedException("결제 실패 상태의 결제만 삭제할 수 있습니다.");
        }

        $this->repository->delete($payment);
    }
}
