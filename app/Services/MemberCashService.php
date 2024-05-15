<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Models\Member;
use App\Models\MemberCash;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberCashTransactionServiceInterface;

class MemberCashService implements MemberCashServiceInterface {
    public function __construct(
        protected MemberCashRepositoryInterface $repository,
        protected MemberCashTransactionServiceInterface $transactionService
    ) {}

    /**
     * @inheritDoc
     */
    public function create(Member $member): MemberCash
    {
        return $this->repository->save($member);
    }

    public function charge(MemberCashDTO $DTO): MemberCash
    {
        $cash = $this->repository->lock($DTO->member);
        $this->transactionService->create($DTO);
        return $this->repository->charge($cash, $DTO->amount);
    }

    /**
     * @inheritDoc
     */
    public function spend(MemberCashDTO $DTO): MemberCash
    {
        if (! $this->check($DTO->member, $DTO->amount)) {
            throw new MemberCashNotEnoughToSpendException('포인트가 부족합니다.');
        }

        $cash = $this->repository->lock($DTO->member);
        $this->transactionService->create($DTO);
        return $this->repository->spend($cash, $DTO->amount);
    }

    public function check(Member $member, int $amount): bool
    {
        return $this->repository->canSpendCheck($member, $amount);
    }
}
