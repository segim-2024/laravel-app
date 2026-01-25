<?php

namespace App\Repositories\Eloquent;

use App\DTOs\GetMileageHistoryListDTO;
use App\Enums\MileageActionEnum;
use App\Models\MileageHistory;
use App\Repositories\Interfaces\MileageHistoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class MileageHistoryRepository extends BaseRepository implements MileageHistoryRepositoryInterface
{
    public function __construct(MileageHistory $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     */
    public function getList(GetMileageHistoryListDTO $DTO): JsonResponse
    {
        $query = MileageHistory::where('mb_no', $DTO->member->mb_no)
            ->when($DTO->start, fn ($q) => $q->where('created_at', '>=', $DTO->start))
            ->when($DTO->end, fn ($q) => $q->where('created_at', '<=', $DTO->end))
            ->when($DTO->keyword, fn ($q) => $q->where(function ($q) use ($DTO) {
                $q->where('context_type', 'like', "%{$DTO->keyword}%")
                    ->orWhere('context_key', 'like', "%{$DTO->keyword}%")
                    ->orWhere('memo', 'like', "%{$DTO->keyword}%");
            }))
            ->when($DTO->filter === 'save', fn ($q) => $q->where('action', MileageActionEnum::Accrue))
            ->when($DTO->filter === 'use', fn ($q) => $q->whereIn('action', [MileageActionEnum::Use, MileageActionEnum::Convert]));

        return DataTables::eloquent($query)
            ->addColumn('action_label', fn ($row) => $row->action->label())
            ->addColumn('action_icon', fn ($row) => $row->action->icon())
            ->addColumn('action_class', fn ($row) => $row->action->amountClass())
            ->addColumn('formatted_date', fn ($row) => $row->created_at->format('Y.m.d'))
            ->addColumn('description', fn ($row) => $row->description)
            ->addColumn('formatted_amount', fn ($row) => $row->formatted_amount)
            ->make();
    }
}
