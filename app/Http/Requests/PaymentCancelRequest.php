<?php

namespace App\Http\Requests;

use App\DTOs\PaymentCancelDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\Interfaces\PaymentInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class PaymentCancelRequest extends FormRequest
{
    public PaymentInterface $payment;

    public function __construct(
        protected MemberPaymentServiceInterface $memberPaymentService,
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
            'payment_id' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'reason' => ['nullable', 'string', 'max:200'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $payment = $this->memberPaymentService->findByKey($this->validated('payment_id'));
                if (! $payment) {
                    throw new NotFoundHttpException("결제 정보를 찾을 수 없어요.");
                }

                if ($payment->state !== MemberPaymentStatusEnum::Paid && $payment->state !== MemberPaymentStatusEnum::PartialCancelled) {
                    throw new PreconditionFailedHttpException("결제, 부분 취소 상태만 취소 처리할 수 있어요.");
                }

                // 기존 취소 금액, 취소 가능 금액 비교
                if ($payment->cancelled_amount + $this->validated('amount') > $payment->amount) {
                    throw new ConflictHttpException("취소 가능한 금액을 초과했어요.");
                }

                $this->payment = $payment;
            }
        ];
    }

    public function toDTO(): PaymentCancelDTO
    {
        return PaymentCancelDTO::createFromRequest($this);
    }
}
