<?php

namespace App\Services\Interfaces;

use App\DTOs\LibraryProductSubscribeDTO;
use App\DTOs\LibraryProductUnsubscribeDTO;
use App\DTOs\RePaymentLibraryProductDTO;
use App\DTOs\UpdateLibraryProductSubscribeCardDTO;
use App\DTOs\UpdateLibraryProductSubscribeDatesDTO;
use App\DTOs\UpdateLibraryProductSubscribeStateToUnpaidDTO;
use App\Exceptions\CardForbbidenException;
use App\Exceptions\LibraryProductSubscribeConflictException;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Models\LibraryProductSubscribe;
use Illuminate\Support\Collection;

interface LibraryProductSubscribeServiceInterface
{
    /**
     * @param LibraryProductSubscribeDTO $DTO
     * @return LibraryProductSubscribe
     * @throws LibraryProductSubscribeConflictException
     */
    public function subscribe(LibraryProductSubscribeDTO $DTO): LibraryProductSubscribe;

    /**
     * @param LibraryProductUnsubscribeDTO $DTO
     * @return LibraryProductSubscribe
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function unsubscribe(LibraryProductUnsubscribeDTO $DTO): LibraryProductSubscribe;

    /**
     * @param UpdateLibraryProductSubscribeCardDTO $DTO
     * @return LibraryProductSubscribe
     * @throws LibraryProductSubscribeNotFoundException
     * @throws CardForbbidenException
     */
    public function updateCard(UpdateLibraryProductSubscribeCardDTO $DTO): LibraryProductSubscribe;
    /**
     * 구독이 가능한 상태 여부를 반환합니다.
     *
     * @param string $memberId
     * @return bool
     */
    public function isCanSubscribe(string $memberId): bool;

    /**
     * @param int $id
     * @return LibraryProductSubscribe|null
     */
    public function findById(int $id): ?LibraryProductSubscribe;

    /**
     * 오늘 결제 예정인 구독 목록을 반환합니다.
     *
     * @return Collection
     */
    public function getSubscriptionsDueToday(): Collection;


    /**
     * 구독 상태의 결제일, 시작일, 종료일을 변경합니다.
     * 결제 성공 시에 호출됩니다.
     *
     * @param UpdateLibraryProductSubscribeDatesDTO $DTO
     * @return LibraryProductSubscribe
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function updateDatesOnSuccess(UpdateLibraryProductSubscribeDatesDTO $DTO): LibraryProductSubscribe;

    /**
     * 구독 상태를 미납으로 변경합니다.
     * 결제 실패 시에 호출됩니다.
     *
     * @param UpdateLibraryProductSubscribeStateToUnpaidDTO $DTO
     * @return LibraryProductSubscribe
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function updateStateToUnpaid(UpdateLibraryProductSubscribeStateToUnpaidDTO $DTO): LibraryProductSubscribe;

    /**
     * @param RePaymentLibraryProductDTO $DTO
     * @return void
     * @throws LibraryProductSubscribeNotFoundException
     * @throws LibraryProductSubscribeConflictException
     */
    public function rePayment(RePaymentLibraryProductDTO $DTO): void;
}
