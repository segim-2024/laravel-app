<?php

namespace App\Http\Controllers;

use App\DTOs\GetMileageHistoryListDTO;
use App\Services\Interfaces\MileageServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class MileageController extends Controller
{
    public function __construct(
        protected MileageServiceInterface $service
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $member = $request->user();

        if (! $member->hasMileageAccess()) {
            return redirect()->route('cards.index')
                ->with('error', '마일리지 사용 권한이 없습니다.');
        }

        $currentBalance = $this->service->getCurrentBalance($member);
        $totalAccrued = $this->service->getTotalAccrued($member);
        $totalConverted = $this->service->getTotalConverted($member);
        $convertibleAmount = $this->service->getConvertibleAmount($member);
        $policy = $this->service->getCurrentPolicy($member);

        // 총 보유 포인트 (추후 포인트 시스템 연동 필요)
        $totalPoints = 0;

        return view('mileage.mileage_list', compact(
            'currentBalance',
            'totalAccrued',
            'totalConverted',
            'convertibleAmount',
            'totalPoints',
            'policy'
        ));
    }

    public function list(Request $request): JsonResponse
    {
        $member = $request->user();

        if (! $member->hasMileageAccess()) {
            return response()->json(['error' => '권한이 없습니다.'], 403);
        }

        $start = $request->input('periodStart') ? Carbon::parse($request->input('periodStart'))->startOfDay() : null;
        $end = $request->input('periodEnd') ? Carbon::parse($request->input('periodEnd'))->endOfDay() : null;

        return $this->service->getList(new GetMileageHistoryListDTO(
            member: $member,
            start: $start,
            end: $end,
            keyword: $request->input('keyword'),
            filter: $request->input('filter'),
        ));
    }
}