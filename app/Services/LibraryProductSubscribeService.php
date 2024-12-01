<?php

namespace App\Services;

use App\DTOs\LibraryProductSubscribeDTO;
use App\DTOs\LibraryProductUnsubscribeDTO;
use App\DTOs\UpdateLibraryProductSubscribeCardDTO;
use App\Exceptions\CardForbbidenException;
use App\Exceptions\LibraryProductSubscribeConflictException;
use App\Exceptions\LibraryProductSubscribeForbbidenException;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Jobs\StartLibraryProductSubscribeNotificationJob;
use App\Jobs\StartSubscribeSendAlimTokJob;
use App\Models\LibraryProductSubscribe;
use App\Repositories\Interfaces\LibraryProductSubscribeRepositoryInterface;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
use Illuminate\Support\Carbon;

class LibraryProductSubscribeService implements LibraryProductSubscribeServiceInterface {
    public function __construct(
        protected MemberCardServiceInterface $cardService,
        protected LibraryProductSubscribeRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function subscribe(LibraryProductSubscribeDTO $DTO): LibraryProductSubscribe
    {
        $subscribe = $this->repository->findByMemberId($DTO->memberId);
        // 구독 정보가 이미 존재하는 경우 처리
        if ($subscribe) {
            if (! $subscribe->state->isUnsubscribe()) {
                throw new LibraryProductSubscribeConflictException('이미 구독/미납 중인 라이브러리 상품이 있습니다.');
            }

            // 해지된 상태에 대한 기록이라면 삭제하고 새로 만듬
            $this->repository->delete($subscribe);
        }

        $attributes = [
            'due_date' => $this->getClosestDueDate($DTO->paymentDay),
        ];

        $subscribe = $this->repository->create(array_merge($DTO->toModelAttribute(), $attributes));

        // 알림톡 발송
        // StartLibraryProductSubscribeNotificationJob::dispatch($subscribe)->afterCommit();
        return $subscribe;
    }

    /**
     * @inheritDoc
     */
    public function unsubscribe(LibraryProductUnsubscribeDTO $DTO): LibraryProductSubscribe
    {
        // 구독 정보 조회
        $subscribe = $this->repository->findByMemberId($DTO->memberId);
        if (! $subscribe) {
            throw new LibraryProductSubscribeNotFoundException('구독 중인 라이브러리 상품이 없습니다.');
        }

        // 구독 정보 상태 '해지' 업데이트
        return $this->repository->update($subscribe, $DTO->toModelAttribute());
    }

    /**
     * @inheritDoc
     */
    public function updateCard(UpdateLibraryProductSubscribeCardDTO $DTO): LibraryProductSubscribe
    {
        // 구독 정보 조회 및 구독 정보 예외 처리
        $subscribe = $this->repository->findByProductId($DTO->productId);
        if (! $subscribe || $subscribe->member_id !== $DTO->memberId) {
            throw new LibraryProductSubscribeForbbidenException('구독 정보가 일치하지 않습니다.');
        }

        // 카드 정보 조회 및 멤버 ID와 비교 체크
        $card = $this->cardService->findById($DTO->cardId);
        if ($card?->member_id !== $DTO->memberId) {
            throw new CardForbbidenException('카드 정보가 일치하지 않습니다.');
        }

        // 구독 정보 상태 카드 ID 업데이트
        return $this->repository->update($subscribe, $DTO->toModelAttribute());
    }

    /**
     * @inheritDoc
     */
    public function isCanSubscribe(string $memberId): bool
    {
        $subscribe = $this->repository->findByMemberId($memberId);
        // 구독 정보가 없는 경우 구독 가능
        if (! $subscribe) {
            return true;
        }

        // 구독 정보 상태 '해지'인지 체크
        if ($subscribe->state->isUnsubscribe()) {
            return true;
        }

        return false;
    }

    /**
     * 약정일에 해당하는 가장 가까운 결제일을 반환합니다.
     *
     * @param int $paymentDay
     * @return Carbon
     */
    private function getClosestDueDate(int $paymentDay): Carbon
    {
        $today = Carbon::today();
        $todayDay = $today->day;

        if ($todayDay > $paymentDay) {
            return $today->addMonthNoOverflow()->day($paymentDay);
        }

        return $today->day($paymentDay);
    }
}
