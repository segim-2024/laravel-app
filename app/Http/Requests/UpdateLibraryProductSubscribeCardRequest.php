<?php

namespace App\Http\Requests;

use App\DTOs\UpdateLibraryProductSubscribeCardDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLibraryProductSubscribeCardRequest extends FormRequest
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
        ];
    }

    public function toDTO(): UpdateLibraryProductSubscribeCardDTO
    {
        return UpdateLibraryProductSubscribeCardDTO::createFromRequest($this);
    }
}
