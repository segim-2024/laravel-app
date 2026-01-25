<?php

namespace App\Services;

use App\DTOs\GetMileageHistoryListDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\MileagePolicy;
use App\Repositories\Factories\MileageBalanceRepositoryFactory;
use App\Repositories\Factories\MileageHistoryRepositoryFactory;
use App\Repositories\Factories\MileagePolicyRepositoryFactory;
use App\Services\Interfaces\MileageServiceInterface;
use Illuminate\Http\JsonResponse;

class MileageService implements MileageServiceInterface
{
    public function __construct(
        protected MileageHistoryRepositoryFactory $historyRepositoryFactory,
        protected MileageBalanceRepositoryFactory $balanceRepositoryFactory,
        protected MileagePolicyRepositoryFactory $policyRepositoryFactory
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getList(GetMileageHistoryListDTO $DTO): JsonResponse
    {
        return $this->historyRepositoryFactory->create($DTO->member)->getList($DTO);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentBalance(MemberInterface $member): int
    {
        $balance = $this->balanceRepositoryFactory->create($member)->findByMbNo($member->mb_no);

        return $balance?->getBalance() ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalAccrued(MemberInterface $member): int
    {
        $balance = $this->balanceRepositoryFactory->create($member)->findByMbNo($member->mb_no);

        return $balance?->getTotalAccrued() ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalConverted(MemberInterface $member): int
    {
        $balance = $this->balanceRepositoryFactory->create($member)->findByMbNo($member->mb_no);

        return $balance?->getTotalConverted() ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getConvertibleAmount(MemberInterface $member): int
    {
        $balance = $this->balanceRepositoryFactory->create($member)->findByMbNo($member->mb_no);
        $policy = $this->getCurrentPolicy($member);
        $convertThreshold = $policy?->convert_threshold ?? 0;

        return $balance?->getConvertibleAmount($convertThreshold) ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentPolicy(MemberInterface $member): ?MileagePolicy
    {
        return $this->policyRepositoryFactory->create($member)->current();
    }
}
