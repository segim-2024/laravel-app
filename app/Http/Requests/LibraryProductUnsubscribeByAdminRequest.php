<?php

namespace App\Http\Requests;

use App\DTOs\LibraryProductUnsubscribeDTO;
use Illuminate\Foundation\Http\FormRequest;

class LibraryProductUnsubscribeByAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:library_products,id'],
            'member_id' => ['required'],
        ];
    }

    public function toDTO(): LibraryProductUnsubscribeDTO
    {
        return LibraryProductUnsubscribeDTO::createFromAdminRequest($this);
    }
}
