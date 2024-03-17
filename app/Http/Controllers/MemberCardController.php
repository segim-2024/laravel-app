<?php

namespace App\Http\Controllers;

use App\DTOs\CreateMemberCardDTO;
use App\DTOs\CreateMemberCardResponseDTO;
use App\Http\Requests\CreateMemberCardRequest;
use App\Models\MemberCard;
use App\Services\Interfaces\MemberCardServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MemberCardController extends Controller
{
    public function __construct(
        protected MemberCardServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        /** @var $cards Collection|MemberCard[] */
        $cards = $this->service->getList($request->user());
        return view('cards.card_list', [
            'cards' => $cards
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

    public function created(CreateMemberCardRequest $request): RedirectResponse
    {
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic dGVzdF9za196WExrS0V5cE5BcldtbzUwblgzbG1lYXhZRzVSOg==',
            ])
            ->post("https://api.tosspayments.com/v1/billing/authorizations/issue", [
                'customerKey' => $request->input('customerKey'),
                'authKey' => $request->input('authKey'),
            ]);

        $response = CreateMemberCardResponseDTO::createFromResponse($response);
        $cardName = "[$response->cardType] $response->cardCompany";
        $this->service->save(new CreateMemberCardDTO(
            $request->user(),
            $cardName,
            $response->cardNumber,
            $response->billingKey
        ));

        return redirect()->route('cards.index');
    }

    public function failed(Request $request)
    {
        /** @var $cards Collection|MemberCard[] */
        $cards = $this->service->getList($request->user());
        return view('cards.card_list', [
            'cards' =>  $cards
        ]);
    }
}
