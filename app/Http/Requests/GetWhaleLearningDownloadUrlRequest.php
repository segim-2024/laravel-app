<?php

namespace App\Http\Requests;

use App\Enums\WhaleLearningPlatformEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class GetWhaleLearningDownloadUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWhale() ?? false;
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', new Enum(WhaleLearningPlatformEnum::class)],
        ];
    }

    public function getPlatform(): WhaleLearningPlatformEnum
    {
        return WhaleLearningPlatformEnum::from($this->validated('platform'));
    }
}
