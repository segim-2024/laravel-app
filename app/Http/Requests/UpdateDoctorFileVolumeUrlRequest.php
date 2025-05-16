<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDoctorFileVolumeUrlDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorFileVolumeUrlRequest extends FormRequest
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
            'url' => ['required', 'string', 'url', 'max:500'],
        ];
    }

    public function toDTO(): UpdateDoctorFileVolumeUrlDTO
    {
        return UpdateDoctorFileVolumeUrlDTO::createFromRequest($this);
    }

    public function isWhale(): bool
    {
        return $this->attributes->get('isWhale');
    }
}
