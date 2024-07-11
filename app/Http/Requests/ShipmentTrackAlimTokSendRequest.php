<?php

namespace App\Http\Requests;

use App\DTOs\ShipmentTrackDTO;
use App\Models\Order;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipmentTrackAlimTokSendRequest extends FormRequest
{
    public Order $order;


    public function __construct(
        protected OrderServiceInterface $service
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
            'od_id' => ['required', 'string'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $order = $this->service->find($this->validated('od_id'));
                if (! $order) {
                    throw new NotFoundHttpException("결제 정보를 찾을 수 없어요.");
                }

                if ($order->od_delivery_company !== "CJ대한통운") {
                    throw new ConflictHttpException("CJ대한통운 송장번호가 입력되지 않았어요.");
                }

                $this->order = $order;
            }
        ];
    }

    public function toDTO():ShipmentTrackDTO
    {
        return ShipmentTrackDTO::createFromRequest($this);
    }
}
