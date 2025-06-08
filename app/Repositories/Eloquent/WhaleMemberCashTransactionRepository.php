<?php
namespace App\Repositories\Eloquent;

use App\DTOs\GetECashHistoryDTO;
use App\DTOs\MemberCashDTO;
use App\Enums\GetECashHistorySearchTypeEnum;
use App\Models\WhaleMemberCashTransaction;
use App\Repositories\Interfaces\MemberCashTransactionRepositoryInterface;
use Illuminate\Support\Collection;

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

    /**
     * @inheritDoc
     */
    public function excel(GetECashHistoryDTO $DTO): Collection
    {
        $searchKeyword = $DTO->searchKeyword;
        $searchType = $DTO->searchType;

        return WhaleMemberCashTransaction::when($searchKeyword, function ($query) use ($searchKeyword, $searchType) {
                if ($searchType === GetECashHistorySearchTypeEnum::ALL) {
                    $query->whereHas('member', function ($query) use ($searchKeyword) {
                        $query->where('mb_id', 'like', '%' . $searchKeyword . '%')
                              ->orWhere('mb_name', 'like', '%' . $searchKeyword . '%');
                    });
                } else if ($searchType === GetECashHistorySearchTypeEnum::MEMBER_ID) {
                    $query->whereHas('member', function ($query) use ($searchKeyword) {
                        $query->where('mb_id', 'like', '%' . $searchKeyword . '%');
                    });
                } else if ($searchType === GetECashHistorySearchTypeEnum::ACADEMY_NAME) {
                    $query->whereHas('member', function ($query) use ($searchKeyword) {
                        $query->where('mb_nick', 'like', '%' . $searchKeyword . '%');
                    });
                }
            })
            ->when($DTO->startDate, function ($query) use ($DTO) {
                $query->whereDate('created_at', '>=', $DTO->startDate);
            })
            ->when($DTO->endDate, function ($query) use ($DTO) {
                $query->whereDate('created_at', '<=', $DTO->endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
