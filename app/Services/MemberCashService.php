<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;
use App\Repositories\Factories\MemberCashRepositoryFactory;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberCashTransactionServiceInterface;

class MemberCashService implements MemberCashServiceInterface {
    public function __construct(
        protected MemberCashRepositoryFactory $repositoryFactory,
        protected MemberCashTransactionServiceInterface $transactionService
    ) {}

    /**
     * @inheritDoc
     */
    public function create(MemberInterface $member): CashInterface
    {
        return $this->repositoryFactory->create($member)->save($member);
    }

    /**
     * @inheritDoc
     */
    public function charge(MemberCashDTO $DTO): CashInterface
    {
        $repository = $this->repositoryFactory->create($DTO->member);
        $cash = $repository->lock($DTO->member) ?? $repository->save($DTO->member);
        $this->transactionService->create($DTO);
        return $repository->charge($cash, $DTO->amount);
    }

    /**
     * @inheritDoc
     */
    public function spend(MemberCashDTO $DTO): CashInterface
    {
        $repository = $this->repositoryFactory->create($DTO->member);
        $cash = $repository->lock($DTO->member) ?? $repository->save($DTO->member);
        if ($cash->getAmount() < $DTO->amount) {
            throw new MemberCashNotEnoughToSpendException('포인트가 부족합니다.');
        }

        $this->transactionService->create($DTO);
        return $repository->spend($cash, $DTO->amount);
    }

    public function check(CashInterface $cash, int $amount): bool
    {
        return $cash->getAmount() > $amount;
    }
}
