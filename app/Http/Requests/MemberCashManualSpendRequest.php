<?php

namespace App\Http\Requests;

use App\DTOs\MemberCashDTO;
use App\Models\Interfaces\MemberInterface;
use App\Services\Interfaces\MemberServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MemberCashManualSpendRequest extends FormRequest
{
    public MemberInterface $member;

    public function __construct(
        protected MemberServiceInterface $memberService
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
                $isWhale = $this->user()?->isWhale() ?? false;
                $member = $this->memberService->find($this->validated('mb_id'), $isWhale);
                if (! $member) {
                    throw new NotFoundHttpException("회원을 찾을 수 없어요.");
                }

                $this->member = $member;
            }
        ];
    }

    public function toDTO():MemberCashDTO
    {
        return MemberCashDTO::createFromMemberCashManualSpendRequest($this);
    }
}
