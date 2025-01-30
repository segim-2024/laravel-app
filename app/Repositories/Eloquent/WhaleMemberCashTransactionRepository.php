<?php
namespace App\Repositories\Eloquent;

use App\DTOs\MemberCashDTO;
use App\Models\WhaleMemberCashTransaction;
use App\Repositories\Interfaces\MemberCashTransactionRepositoryInterface;

class WhaleMemberCashTransactionRepository extends BaseRepository implements MemberCashTransactionRepositoryInterface
{
    public function __construct(WhaleMemberCashTransaction $model)
    {
        parent::__construct($model);
    }

    public function save(MemberCashDTO $DTO): WhaleMemberCashTransaction
    {
        $transaction = new WhaleMemberCashTransaction();
        $transaction->member_id = $DTO->member->getMemberId();
        $transaction->type = $DTO->type;
        $transaction->title = $DTO->title;
        $transaction->amount = $DTO->amount;
        $transaction->transactionable()->associate($DTO->transactionable);
        $transaction->save();
        return $transaction;
    }
}
