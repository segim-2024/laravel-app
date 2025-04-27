<?php

namespace App\Http\Requests;

use App\Enums\SegimTicketMinusTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class OrderSegimTicketMinusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(SegimTicketMinusTypeEnum::class)],
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'string'],
        ];
    }
}
