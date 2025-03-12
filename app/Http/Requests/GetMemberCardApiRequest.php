<?php

namespace App\Http\Requests;

use App\DTOs\GetMemberCardApiDTO;
use Illuminate\Foundation\Http\FormRequest;

class GetMemberCardApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): GetMemberCardApiDTO
    {
        return GetMemberCardApiDTO::createFromRequest($this);
    }
}
