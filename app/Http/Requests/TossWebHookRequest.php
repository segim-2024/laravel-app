<?php

namespace App\Http\Requests;

use App\DTOs\TossPaymentResponseDTO;
use Illuminate\Foundation\Http\FormRequest;

class TossWebHookRequest extends FormRequest
{
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
            'data' => ['required'],
        ];
    }

    public function toDTO():TossPaymentResponseDTO
    {
        return TossPaymentResponseDTO::createFromWebHook($this);
    }
}
