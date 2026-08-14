<?php

namespace App\Http\Requests;

use App\Models\Interfaces\MemberInterface;
use App\Services\Interfaces\MemberServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SignInRequest extends FormRequest
{
    public MemberInterface $member;

    public function __construct(
        protected MemberServiceInterface $service
    ) {
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
            'is_whale' => ['required', 'boolean'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // 앞선 규칙이 실패했으면 validated() 접근 시 예외가 나므로 422로 응답되도록 중단한다
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $member = $this->service->find($this->validated('mb_id'), $this->validated('is_whale'));
                if (! $member) {
                    throw new NotFoundHttpException('회원을 찾을 수 없어요.');
                }

                $this->member = $member;
            },
        ];
    }
}
