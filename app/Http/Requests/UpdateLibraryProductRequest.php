<?php

namespace App\Http\Requests;

use App\DTOs\UpdateLibraryProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLibraryProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'ticket_provide_qty' => ['required', 'integer', 'min:0'],
            'is_hided' => ['required', 'boolean'],
        ];
    }

    public function toDTO(): UpdateLibraryProductDTO
    {
        return UpdateLibraryProductDTO::createFromRequest($this);
    }
}
