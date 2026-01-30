<?php

namespace App\Http\Controllers;

use App\DTOs\GetMileageHistoryListDTO;
use App\Http\Requests\ConvertMileageToPointRequest;
use App\Services\Interfaces\MileageServiceInterface;
use App\Services\Interfaces\PamusPointApiServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class MileageController extends Controller
{
    public function __construct(
        protected MileageServiceInterface $service,
        protected PamusPointApiServiceInterface $pamusPointApiService
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

        $totalPoints = $member->mb_point ?? 0;

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

    public function status(Request $request): JsonResponse
    {
        $member = $request->user();

        if (! $member->hasMileageAccess()) {
            return response()->json(['error' => '권한이 없습니다.'], 403);
        }

        return response()->json([
            'currentBalance' => $this->service->getCurrentBalance($member),
            'convertibleAmount' => $this->service->getConvertibleAmount($member),
            'totalPoints' => $member->mb_point ?? 0,
        ]);
    }

    public function convert(ConvertMileageToPointRequest $request): JsonResponse
    {
        $member = $request->user();

        if (! $member->hasMileageAccess()) {
            return response()->json(['error' => '마일리지 사용 권한이 없습니다.'], Response::HTTP_FORBIDDEN);
        }

        $amount = $request->validated('amount');
        $convertibleAmount = $this->service->getConvertibleAmount($member);

        if ($amount > $convertibleAmount) {
            return response()->json(['error' => '전환 가능한 마일리지가 부족합니다.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $uniqueIdx = 'TRX_'.time();

        $response = $this->pamusPointApiService->convert(
            mbId: $member->mb_id,
            amount: $amount,
            uniqueIdx: $uniqueIdx,
            content: '마일리지 포인트 전환'
        );

        if (! $response->isSuccess) {
            return response()->json(['error' => $response->message ?? '포인트 전환에 실패했습니다.'], Response::HTTP_BAD_GATEWAY);
        }

        return response()->json([
            'success' => true,
            'message' => '포인트 전환이 완료되었습니다.',
            'mileage_balance' => $response->mileageBalance,
            'point_balance' => $response->pointBalance,
        ]);
    }
}