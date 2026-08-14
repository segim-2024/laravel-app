<?php

namespace App\Http\Requests;

use App\DTOs\DeleteLectureFileDTO;
use App\Enums\LectureFileTypeEnum;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class DeleteLectureFileRequest extends FormRequest
{
    /**
     * 파머스(새김) 회원만 강의 파일을 삭제할 수 있다.
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
            'type' => ['required', 'string', new Enum(LectureFileTypeEnum::class)],
            'file_name' => [
                'required',
                'string',
                static function (string $attribute, mixed $value, Closure $fail): void {
                    if (! LectureFileTypeEnum::isValidFileName($value)) {
                        $fail('올바른 파일명이 아닙니다.');
                    }
                },
            ],
        ];
    }

    public function getDTO(): DeleteLectureFileDTO
    {
        return new DeleteLectureFileDTO(
            LectureFileTypeEnum::from($this->validated('type')),
            $this->validated('file_name')
        );
    }
}
