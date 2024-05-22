<?php

namespace App\Http\Requests;

use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Services\Interfaces\MemberServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;

class UpdateActivateMemberSubscribeProductRequest extends FormRequest
{
    public Member $member;
    public Product $product;
    public MemberSubscribeProduct $subscribe;

    public function __construct(
        protected MemberServiceInterface $memberService,
        protected ProductServiceInterface $productService,
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
            'mb_id' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (! $this->route('productId')) {
                    throw new NotFoundHttpException("상품을 찾을 수 없어요.");
                }

                $product = $this->productService->find($this->route('productId'));
                if (! $product) {
                    throw new NotFoundHttpException("회원을 찾을 수 없어요.");
                }

                $member = $this->memberService->find($this->validated('mb_id'));
                if (! $member) {
                    throw new NotFoundHttpException("회원을 찾을 수 없어요.");
                }

                $subscribe = $this->memberSubscribeProductService->findByMemberAndProduct($member, $product);
                if (! $subscribe) {
                    throw new PreconditionRequiredHttpException("결제 상품에 해당하는 구독 정보를 찾을 수 없어요.");
                }

                $this->member = $member;
                $this->product = $product;
                $this->subscribe = $subscribe;
            }
        ];
    }

    public function toDTO():UpdateActivateMemberSubscribeProductDTO
    {
        return UpdateActivateMemberSubscribeProductDTO::createFromRequest($this);
    }
}
