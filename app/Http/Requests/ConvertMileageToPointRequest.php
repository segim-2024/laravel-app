<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertMileageToPointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => '전환 금액을 입력해주세요.',
            'amount.integer' => '전환 금액은 숫자여야 합니다.',
            'amount.min' => '전환 금액은 1원 이상이어야 합니다.',
        ];
    }
}