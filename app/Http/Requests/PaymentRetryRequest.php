<?php

namespace App\Http\Requests;

use App\DTOs\PaymentRetryDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\MemberPayment;
use App\Models\MemberSubscribeProduct;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;

class PaymentRetryRequest extends FormRequest
{
    public MemberPayment $payment;
    public MemberSubscribeProduct $subscribe;

    public function __construct(
        protected MemberPaymentServiceInterface $memberPaymentService,
        protected MemberSubscribeProductServiceInterface $memberSubscribeProductService
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
        ];
    }


    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $payment = $this->memberPaymentService->findFailedPayment($this->validated('payment_id'));
                if (! $payment) {
                    throw new NotFoundHttpException("결제 정보를 찾을 수 없어요.");
                }

                if ($payment->state !== MemberPaymentStatusEnum::Aborted) {
                    throw new ConflictHttpException("이미 처리된 결제에요.");
                }

                $subscribe = $this->memberSubscribeProductService->findByMemberAndProduct($payment->member, $payment->productable);
                if (! $subscribe) {
                    throw new PreconditionRequiredHttpException("결제 상품에 해당하는 구독 정보를 찾을 수 없어요.");
                }

                $this->payment = $payment;
                $this->subscribe = $subscribe;
            }
        ];
    }

    public function toDTO():PaymentRetryDTO
    {
        return PaymentRetryDTO::createFromRequest($this);
    }
}
