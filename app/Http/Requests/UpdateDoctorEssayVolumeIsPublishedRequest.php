<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDoctorEssayVolumeIsPublishedDTO;
use App\Http\Requests\Interfaces\HasReferrerCheck;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorEssayVolumeIsPublishedRequest extends FormRequest implements HasReferrerCheck
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
            'is_published' => ['required', 'boolean'],
        ];
    }

    public function toDTO(): UpdateDoctorEssayVolumeIsPublishedDTO
    {
        return UpdateDoctorEssayVolumeIsPublishedDTO::createFromRequest($this);
    }

    public function isWhale(): bool
    {
        return $this->attributes->get('isWhale');
    }
}
