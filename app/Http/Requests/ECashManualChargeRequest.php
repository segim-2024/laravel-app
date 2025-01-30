<?php

namespace App\Http\Requests;

use App\DTOs\MemberCashDTO;
use Illuminate\Foundation\Http\FormRequest;

class ECashManualChargeRequest extends FormRequest
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
            'amount' => ['required', 'numeric'],
            'title' => ['required', 'string'],
        ];
    }

    public function toDTO(): MemberCashDTO
    {
        return MemberCashDTO::createFromECashManualChargeRequest($this);
    }
}
