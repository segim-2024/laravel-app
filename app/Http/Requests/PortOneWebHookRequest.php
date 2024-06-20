<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortOneWebHookRequest extends FormRequest
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
            'payment_id' => ['required', 'string'],
            'tx_id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ];
    }
}
