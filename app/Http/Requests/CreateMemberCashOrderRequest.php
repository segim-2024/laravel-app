<?php

namespace App\Http\Requests;

use App\DTOs\MemberCashDTO;
use App\Models\Member;
use App\Models\Order;
use App\Services\Interfaces\MemberServiceInterface;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateMemberCashOrderRequest extends FormRequest
{
    public Member $member;
    public Order $order;

    public function __construct(
        protected MemberServiceInterface $memberService,
        protected OrderServiceInterface $orderService
    )
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mb_id' => ['required', 'string'],
            'od_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'title' => ['required', 'string'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $member = $this->memberService->find($this->validated('mb_id'));
                if (! $member) {
                    throw new NotFoundHttpException("회원을 찾을 수 없어요.");
                }

                $order = $this->orderService->find($this->validated('od_id'));

                if (! $order) {
                    throw new ConflictHttpException("주문을 찾을 수 없습니다.");
                }

                $this->member = $member;
                $this->order = $order;
            }
        ];
    }

    public function toDTO():MemberCashDTO
    {
        return MemberCashDTO::createFromMemberCashOrderRequest($this);
    }
}
