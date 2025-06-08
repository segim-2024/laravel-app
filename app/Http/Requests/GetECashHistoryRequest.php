<?php

namespace App\Http\Requests;

use App\DTOs\GetECashHistoryDTO;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetECashHistoryRequest extends FormRequest
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
        } else if (str_contains($referer, 'https://englishwhale.com')) {
            $this->isWhale = true;
        }

        throw new AccessDeniedHttpException('허용되지 않는 접근입니다.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

        ];
    }

    public function toDTO(): GetECashHistoryDTO
    {
        return GetECashHistoryDTO::createFromRequest($this);
    }
}
