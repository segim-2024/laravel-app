<?php

namespace App\Http\Controllers;

use App\Exceptions\PortOneGetBillingKeyException;
use App\Http\Requests\CreateMemberCardRequest;
use App\Models\MemberCard;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\TossServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MemberCardController extends Controller
{
    public function __construct(
        protected MemberCardServiceInterface $service,
        protected TossServiceInterface       $tossService,
    ) {}

    public function index(Request $request)
    {
        /** @var $cards Collection|MemberCard[] */
        $cards = $this->service->getList($request->user());
        return view('cards.card_list', [
            'cards' => $cards,
            'channelKey' => Config::get('services.portone.v2.channel_key')
        ]);
    }

    public function isExists(Request $request): JsonResponse
    {
        /** @var $cards Collection|MemberCard[] */
        $cards = $this->service->getList($request->user());
        if ($cards->count() > 0) {
            return response()->json(null, 200);
        }

        return response()->json(null, 204);
    }

    public function store(CreateMemberCardRequest $request): JsonResponse
    {
        try {
            $card = $this->service->save($request->toDTO());
        } catch (PortOneGetBillingKeyException $exception) {
            Log::error($exception);
            return response()->json(['message' => '빌링키 정보가 잘못되었어요. 다시 신청해주세요.'], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($card, Response::HTTP_CREATED);
    }
}
