<?php

namespace App\Http\Requests;

use App\DTOs\CreateDoctorFileNoticeDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateDoctorFileNoticeRequest extends FormRequest
{
    public bool $isWhale;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->mb_level < 10) {
            throw new AccessDeniedHttpException("권한이 없습니다.");
        }

        $referer = $this->headers->get('referer');
        if (str_contains($referer, 'https://epamus.com')) {
            $this->isWhale = false;
        } elseif (str_contains($referer, 'https://englishwhale.com')) {
            $this->isWhale = true;
        } else {
//            if (! $referer && Config::get('app.env') === 'local') {
//                $this->isWhale = true;
//            }

            $this->isWhale = true;
            // throw new AccessDeniedHttpException('허용되지 않는 접근입니다.');
        }

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
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string'],
        ];
    }

    public function toDTO(): CreateDoctorFileNoticeDTO
    {
        return CreateDoctorFileNoticeDTO::createFromRequest($this);
    }
}
