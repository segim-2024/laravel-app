<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;
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
    public function create(MemberInterface $member): CashInterface
    {
        return $this->repository->save($member);
    }

    public function charge(MemberCashDTO $DTO): CashInterface
    {
        if (! $DTO->member->getCash()) {
            $this->create($DTO->member);
        }

        $cash = $this->repository->lock($DTO->member);
        $this->transactionService->create($DTO);
        return $this->repository->charge($cash, $DTO->amount);
    }

    /**
     * @inheritDoc
     */
    public function spend(MemberCashDTO $DTO): CashInterface
    {
        if (! $DTO->member->getCash()) {
            $this->create($DTO->member);
        }

        if (! $this->check($DTO->member, $DTO->amount)) {
            throw new MemberCashNotEnoughToSpendException('포인트가 부족합니다.');
        }

        $cash = $this->repository->lock($DTO->member);
        $this->transactionService->create($DTO);
        return $this->repository->spend($cash, $DTO->amount);
    }

    public function check(MemberInterface $member, int $amount): bool
    {
        return $this->repository->canSpendCheck($member, $amount);
    }
}
