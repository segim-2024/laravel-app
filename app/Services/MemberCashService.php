<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\Models\Member;
use App\Models\MemberCash;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;
use App\Services\Interfaces\MemberCashServiceInterface;

class MemberCashService implements MemberCashServiceInterface {
    public function __construct(
        protected MemberCashRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function create(Member $member): MemberCash
    {
        return $this->repository->save($member);
    }

    /**
     * @inheritDoc
     */
    public function spend(MemberCashDTO $DTO): MemberCash
    {
        if (! $this->check($DTO->company, $DTO->amount)) {
            throw new CompanyPointException('포인트가 부족합니다.', 422);
        }

        $point = $this->repository->lock($DTO->company);
        $companyPoint = $this->repository->spend($point, $DTO->amount);
        $this->transactionService->create($DTO);
        CompanyPointEvent::dispatch($DTO->company, EventTypeEnum::UPDATED);
        return $companyPoint;
    }

    /**
     * @inheritDoc
     */
    public function charge(CompanyPointDTO $DTO): CompanyPoint
    {
        $point = $this->repository->lock($DTO->company);
        $companyPoint = $this->repository->charge($point, $DTO->amount);
        $this->transactionService->create($DTO);
        CompanyPointEvent::dispatch($DTO->company, EventTypeEnum::UPDATED);
        return $companyPoint;
    }
}
