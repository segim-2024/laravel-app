<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDoctorEssayMaterialDTO;
use App\Http\Requests\Interfaces\HasReferrerCheck;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorEssayMaterialRequest extends FormRequest implements HasReferrerCheck
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
            'title'         => ['required', 'string', 'max:100'],
            'color_code'    => ['nullable', 'string', 'max:20'],
            'bg_color_code' => ['nullable', 'string', 'max:20'],
            'file'          => ['nullable', 'mimes:pdf,pptx,ppt,doc,docx,xlsx,xls,csv,zip,png,jpeg,webp,jpg,gif,mp4', 'max:30720'],
        ];
    }

    public function toDTO(): UpdateDoctorEssayMaterialDTO
    {
        return UpdateDoctorEssayMaterialDTO::createFromRequest($this);
    }

    public function isWhale(): bool
    {
        return $this->attributes->get('isWhale');
    }
}
