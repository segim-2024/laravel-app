<?php

namespace App\Http\Requests;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\MemberCard;
use App\Models\Product;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpsertMemberSubscribeProductRequest extends FormRequest
{
    public Product $product;
    public MemberCard $card;

    public function __construct(
        protected ProductServiceInterface $productService,
        protected MemberCardServiceInterface $cardService,
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
            'product_id' => ['required', 'exists:App\Models\Product,id'],
            'card_id' => ['required'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $product = $this->productService->find($this->validated('product_id'));
                if (! $product) {
                    throw new NotFoundHttpException('상품 정보를 찾을 수 없어요');
                }

                $this->product = $product;

                $card = $this->cardService->find($this->user(), $this->validated('card_id'));
                if (! $card) {
                    throw new NotFoundHttpException('카드 정보를 찾을 수 없어요');
                }

                $this->card = $card;
            }
        ];
    }

    public function toDTO():UpsertMemberSubscribeProductDTO
    {
        return UpsertMemberSubscribeProductDTO::createFromRequest($this);
    }
}
