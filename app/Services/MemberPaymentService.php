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
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\PaymentInterface;
use App\Models\LibraryProduct;
use App\Models\MemberPayment;
use App\Models\Product;
use App\Models\WhaleProduct;
use App\Repositories\Factories\MemberPaymentRepositoryFactory;
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
        protected ProductPaymentRepositoryInterface $productPaymentRepository,
        protected MemberPaymentRepositoryFactory    $repositoryFactory
    ) {}

    /**
     * @inheritDoc
     */
    public function findByKey(string $key): ?PaymentInterface
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
    public function findFailedPayment(string $paymentId): ?PaymentInterface
    {
        return $this->repository->findFailedPayment($paymentId);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(MemberInterface $member): int
    {
        return $this->productPaymentRepository->getTotalAmount($member->getMemberId());
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(MemberInterface $member): int
    {
        return $this->productPaymentRepository->getTotalPaymentCount($member->getMemberId());
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberPaymentDTO $DTO): PaymentInterface
    {
        return $this->repositoryFactory->create($DTO->member)->save($DTO);
    }

    /**
     * @inheritDoc
     */
    public function retry(PaymentRetryDTO $DTO): PaymentInterface
    {
        if ($DTO->payment->getCardId() !== $DTO->subscribe->getCardId()) {
            $DTO->payment = $this->updateCard($DTO->payment, $DTO->subscribe->getCard());
        }

        $this->portOneService->requestPaymentByBillingKey($DTO->subscribe->getCard()->getKey(), $DTO->payment);
        return $DTO->payment;
    }

    /**
     * @inheritDoc
     */
    public function cancel(PaymentCancelDTO $DTO): PaymentInterface
    {
        $this->portOneService->cancel($DTO);
        return $DTO->payment;
    }

    /**
     * @inheritDoc
     */
    public function updateCard(PaymentInterface $payment, CardInterface $card): PaymentInterface
    {
        return $this->repository->updateCard($payment, $card);
    }

    /**
     * @inheritDoc
     */
    public function process(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
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
    private function processPaid(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
    {
        if ($payment->getState() === $DTO->status) {
            return $payment;
        }

        $productable = $payment->productable;
        // 상품에 따른 결제 완료 처리
        match (true) {
            $productable instanceof Product => $this->productPaymentService->processPaid($payment, $DTO),
            $productable instanceof LibraryProduct => $this->libraryPaymentService->processPaid($payment, $DTO),
            $productable instanceof WhaleProduct => null, // Whale 상품은 별도 처리 없음
            default => null,
        };

        return $this->repository->updateDone($payment, $DTO);
    }

    private function processCancelled(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
    {
        if ($payment->getState() === MemberPaymentStatusEnum::Cancelled) {
            return $payment;
        }

        return $this->repository->updateCanceled($payment, $DTO);
    }

    /**
     * @throws LibraryProductSubscribeNotFoundException
     */
    private function processFailed(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
    {
        $productable = $payment->productable;
        // 상품에 따른 결제 실패 처리
        match (true) {
            $productable instanceof Product => $this->productPaymentService->processFailed($payment),
            $productable instanceof LibraryProduct => $this->libraryPaymentService->processFailed($payment),
            $productable instanceof WhaleProduct => null, // Whale 상품은 별도 처리 없음
            default => null,
        };

        return $this->repository->updateFailed($payment, $DTO);
    }

    /**
     * @inheritDoc
     */
    public function manuallySetFailed(PaymentInterface $payment, string $api): PaymentInterface
    {
        $productable = $payment->productable;
        // 상품에 따른 결제 실패 처리
        match (true) {
            $productable instanceof Product => $this->productPaymentService->processFailed($payment),
            $productable instanceof LibraryProduct => $this->libraryPaymentService->processFailed($payment),
            $productable instanceof WhaleProduct => null, // Whale 상품은 별도 처리 없음
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

        if (! $payment->getState()->isFailed()) {
            throw new PaymentIsNotFailedException("결제 실패 상태의 결제만 삭제할 수 있습니다.");
        }

        $this->repository->delete($payment);
    }
}
