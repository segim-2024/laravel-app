<?php

namespace App\Http\Requests;

use App\DTOs\GetListDoctorFileSeriesDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetListDoctorFileSeriesRequest extends FormRequest
{
    public bool $isWhale;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 리퍼러를 확인합니다.
        $referer = $this->headers->get('referer');
        Log::info($referer);
        if (! $referer && Config::get('app.env') === 'local') {
            $this->isWhale = true;
            return true;
        }

        if (str_contains($referer, 'https://epamus.com')) {
            $this->isWhale = false;
        } elseif (str_contains($referer, 'https://englishwhale.com')) {
            $this->isWhale = true;
        } else {
            throw new AccessDeniedHttpException('허용되지 않는 접근입니다.');
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
            //
        ];
    }

    public function toDTO(): GetListDoctorFileSeriesDTO
    {
        return GetListDoctorFileSeriesDTO::createFromRequest($this);
    }
}
