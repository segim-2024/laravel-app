<?php

namespace App\Http\Requests;

use App\DTOs\CreateMemberCardDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateMemberCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'customerKey' => $this->input('customerKey'),
            'authKey' => $this->input('authKey'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customerKey' => ['required', 'string'],
            'authKey' => ['required', 'string'],
        ];
    }
}
