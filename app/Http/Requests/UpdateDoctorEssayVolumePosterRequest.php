<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDoctorEssayVolumePosterDTO;
use App\Http\Requests\Interfaces\HasReferrerCheck;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorEssayVolumePosterRequest extends FormRequest implements HasReferrerCheck
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->mb_level >= 10;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'poster' => ['required', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];
    }

    public function toDTO(): UpdateDoctorEssayVolumePosterDTO
    {
        return UpdateDoctorEssayVolumePosterDTO::createFromRequest($this);
    }

    public function isWhale(): bool
    {
        return $this->attributes->get('isWhale');
    }
}
