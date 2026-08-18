<?php

namespace App\Http\Requests;

use App\DTOs\DeleteS3FileDTO;
use App\Enums\S3FileTypeEnum;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class DeleteS3FileRequest extends FormRequest
{
    /**
     * 파머스(새김) 회원만 파일을 삭제할 수 있다.
     */
    public function authorize(): bool
    {
        return $this->user()?->isWhale() === false;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('type'))) {
            $this->merge(['type' => strtolower(trim($this->input('type')))]);
        }
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', new Enum(S3FileTypeEnum::class)],
            'file_name' => [
                'required',
                'string',
                static function (string $attribute, mixed $value, Closure $fail): void {
                    if (! S3FileTypeEnum::isValidFileName($value)) {
                        $fail('올바른 파일명이 아닙니다.');
                    }
                },
            ],
        ];
    }

    public function getDTO(): DeleteS3FileDTO
    {
        return new DeleteS3FileDTO(
            S3FileTypeEnum::from($this->validated('type')),
            $this->validated('file_name')
        );
    }
}
