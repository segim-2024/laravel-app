<?php

namespace App\Http\Requests;

use App\DTOs\LibraryProductSubscribeDTO;
use Illuminate\Foundation\Http\FormRequest;

class LibraryProductSubscribeRequest extends FormRequest
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
            'card_id' => ['required'],
            'payment_day' => ['required', 'string', 'min:1', 'max:28'],
        ];
    }

    public function toDTO(): LibraryProductSubscribeDTO
    {
        return LibraryProductSubscribeDTO::createFromRequest($this);
    }
}
