<?php

namespace App\Http\Requests;

use App\DTOs\MemberCashDTO;
use App\Models\Interfaces\OrderInterface;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ECashOrderRequest extends FormRequest
{
    public OrderInterface $order;

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
            'od_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'title' => ['required', 'string'],
        ];
    }


    /**
     * Get the "after" validation callables for the request.
     */
    public function after(OrderServiceInterface $orderService): array
    {
        return [
            function (Validator $validator) use($orderService) {
                $order = $orderService->findWithPlatform($this->validated('od_id'), $this->user()->isWhale());
                if (! $order) {
                    throw new ConflictHttpException("주문을 찾을 수 없습니다.");
                }

                $this->order = $order;
            }
        ];
    }

    public function toDTO():MemberCashDTO
    {
        return MemberCashDTO::createFromECashOrderRequest($this);
    }
}
