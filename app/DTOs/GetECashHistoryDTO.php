<?php

namespace App\DTOs;

use App\Enums\GetECashHistorySearchTypeEnum;
use App\Http\Requests\GetECashHistoryRequest;
use App\Models\Interfaces\MemberInterface;
use Carbon\Carbon;

class GetECashHistoryDTO
{
    public function __construct(
        public MemberInterface $member,
        public GetECashHistorySearchTypeEnum $searchType,
        public ?string $searchKeyword = null,
        public ?Carbon $startDate = null,
        public ?Carbon $endDate = null,
    ) {}

    public static function createFromRequest(GetECashHistoryRequest $request): self
    {
        $searchType = GetECashHistorySearchTypeEnum::tryFrom($request->input('searchType', 'all'));
        if (! $searchType) {
            $searchType = GetECashHistorySearchTypeEnum::ALL;
        }

        $searchKeyword = $request->input('searchKeyword');
        $startDate = $request->input('startDate') ? Carbon::parse($request->input('startDate')) : null;
        $endDate = $request->input('endDate') ? Carbon::parse($request->input('endDate')) : null;

        return new self(
            member: $request->user(),
            searchType: $searchType,
            searchKeyword: $searchKeyword,
            startDate: $startDate,
            endDate: $endDate,
        );
    }
}
