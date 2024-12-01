<?php

namespace App\Services\Interfaces;

use App\DTOs\LibraryProductSubscribeDTO;
use App\DTOs\LibraryProductUnsubscribeDTO;
use App\DTOs\UpdateLibraryProductSubscribeCardDTO;
use App\Exceptions\CardForbbidenException;
use App\Exceptions\LibraryProductSubscribeConflictException;
use App\Exceptions\LibraryProductSubscribeForbbidenException;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Models\LibraryProductSubscribe;

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
     * @throws LibraryProductSubscribeForbbidenException
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
}
