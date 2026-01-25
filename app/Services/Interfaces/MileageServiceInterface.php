<?php

namespace App\Services\Interfaces;

use App\DTOs\GetMileageHistoryListDTO;
use App\Models\Interfaces\MemberInterface;
use Illuminate\Http\JsonResponse;

interface MileageServiceInterface
{
    /**
     * 마일리지 이력 목록 조회 (DataTables)
     *
     * @param GetMileageHistoryListDTO $DTO
     * @return JsonResponse
     */
    public function getList(GetMileageHistoryListDTO $DTO): JsonResponse;

    /**
     * 현재 보유 마일리지 조회
     *
     * @param MemberInterface $member
     * @return int
     */
    public function getCurrentBalance(MemberInterface $member): int;

    /**
     * 총 누적 적립 조회
     *
     * @param MemberInterface $member
     * @return int
     */
    public function getTotalAccrued(MemberInterface $member): int;

    /**
     * 총 누적 전환 조회
     *
     * @param MemberInterface $member
     * @return int
     */
    public function getTotalConverted(MemberInterface $member): int;

    /**
     * 포인트 전환 가능 마일리지 조회
     *
     * @param MemberInterface $member
     * @return int
     */
    public function getConvertibleAmount(MemberInterface $member): int;

    /**
     * 현재 적용 중인 마일리지 정책 조회
     *
     * @param MemberInterface $member
     * @return \App\Models\MileagePolicy|null
     */
    public function getCurrentPolicy(MemberInterface $member): ?\App\Models\MileagePolicy;
}