<?php
namespace App\Repositories\Eloquent;

use App\DTOs\MemberCashDTO;
use App\Models\MemberCashTransaction;
use App\Repositories\Interfaces\MemberCashTransactionRepositoryInterface;

class MemberCashTransactionRepository extends BaseRepository implements MemberCashTransactionRepositoryInterface
{
    public function __construct(MemberCashTransaction $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function save(MemberCashDTO $DTO): MemberCashTransaction
    {
        $transaction = new MemberCashTransaction();
        $transaction->member_id = $DTO->member->getMemberId();
        $transaction->type = $DTO->type;
        $transaction->title = $DTO->title;
        $transaction->amount = $DTO->amount;
        $transaction->transactionable()->associate($DTO->transactionable);
        $transaction->save();
        return $transaction;
    }
}
