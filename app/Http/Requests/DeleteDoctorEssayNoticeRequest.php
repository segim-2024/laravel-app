<?php

namespace App\Http\Requests;

use App\DTOs\DeleteDoctorEssayNoticeDTO;
use App\Http\Requests\Interfaces\HasReferrerCheck;
use Illuminate\Foundation\Http\FormRequest;

class DeleteDoctorEssayNoticeRequest extends FormRequest implements HasReferrerCheck
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
            //
        ];
    }

    public function toDTO(): DeleteDoctorEssayNoticeDTO
    {
        return DeleteDoctorEssayNoticeDTO::createFromRequest($this);
    }

    public function isWhale(): bool
    {
        return $this->attributes->get('isWhale');
    }
}
