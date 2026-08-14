<?php

namespace App\Http\Requests;

use App\DTOs\CreateLecturePresignedUrlDTO;
use App\Enums\LectureFileTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateLecturePresignedUrlRequest extends FormRequest
{
    /**
     * 파머스(새김) 회원만 강의 파일을 업로드할 수 있다.
     */
    public function authorize(): bool
    {
        return $this->user()?->isWhale() === false;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        if (is_string($this->input('type'))) {
            $normalized['type'] = strtolower(trim($this->input('type')));
        }

        if (is_string($this->input('extension'))) {
            $normalized['extension'] = strtolower(ltrim(trim($this->input('extension')), '.'));
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        // ca_idx1, ca_idx2는 가비아가 계속 전송하지만 파일명이 UUID로 바뀌어 사용하지 않는다 (검증 없이 무시)
        $rules = [
            'type' => ['required', 'string', new Enum(LectureFileTypeEnum::class)],
            'extension' => ['required', 'string'],
        ];

        // 유형이 유효할 때만 확장자 화이트리스트를 적용한다 (유형 오류가 확장자 오류로 오인되는 것을 방지)
        if ($type = $this->resolveType()) {
            $rules['extension'][] = Rule::in($type->allowedExtensions());
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'extension.in' => '해당 유형에서 허용하지 않는 확장자입니다.',
        ];
    }

    public function getDTO(): CreateLecturePresignedUrlDTO
    {
        return new CreateLecturePresignedUrlDTO(
            LectureFileTypeEnum::from($this->validated('type')),
            $this->validated('extension')
        );
    }

    /**
     * 확장자 화이트리스트 결정을 위해 검증 전 단계에서 유형을 해석한다.
     */
    private function resolveType(): ?LectureFileTypeEnum
    {
        $type = $this->input('type');

        return is_string($type) ? LectureFileTypeEnum::tryFrom($type) : null;
    }
}
