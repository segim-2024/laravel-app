<?php

namespace App\Enums;

use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

/**
 * 강의 파일 유형 (S3 segim-edu 버킷 업로드 대상)
 */
enum LectureFileTypeEnum: string
{
    case Video = 'video';
    case EduFile = 'edufile';

    /**
     * S3 키 prefix
     */
    public function prefix(): string
    {
        return match ($this) {
            self::Video => 'media/lecture/video/',
            self::EduFile => 'media/lecture/edufile/',
        };
    }

    /**
     * 업로드 허용 확장자 목록 (소문자)
     *
     * @return array<int, string>
     */
    public function allowedExtensions(): array
    {
        return match ($this) {
            self::Video => ['mp4', 'mov', 'm4v'],
            self::EduFile => ['pdf', 'zip', 'hwp', 'hwpx', 'ppt', 'pptx', 'doc', 'docx', 'xls', 'xlsx'],
        };
    }

    /**
     * S3 키 생성
     */
    public function buildKey(string $fileName): string
    {
        return $this->prefix().$fileName;
    }

    /**
     * 확장자에 대응하는 Content-Type
     */
    public function contentType(string $extension): string
    {
        return MimeTypes::getDefault()->getMimeTypes($extension)[0] ?? 'application/octet-stream';
    }

    /**
     * prefix 밖으로 벗어나지 않는 파일명인지 여부 (경로 탈출 차단용)
     */
    public static function isValidFileName(string $fileName): bool
    {
        return $fileName !== ''
            && ! Str::startsWith($fileName, '.')
            && ! Str::contains($fileName, ['/', '\\', '..']);
    }
}
