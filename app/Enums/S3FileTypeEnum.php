<?php

namespace App\Enums;

use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

/**
 * S3 segim-edu 버킷 업로드 대상 파일 유형
 */
enum S3FileTypeEnum: string
{
    case Video = 'video';
    case EduFile = 'edufile';
    case BoardFile = 'board_file';

    /**
     * S3 키 prefix
     */
    public function prefix(): string
    {
        return match ($this) {
            self::Video => 'media/lecture/video/',
            self::EduFile => 'media/lecture/edufile/',
            self::BoardFile => 'board/files/',
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
            // 본부장 게시판은 기존에 확장자 제한이 없던 기능이라 업무 파일을 넓게 허용한다.
            // 제외 대상: svg/html(공개 버킷에서 스크립트 실행 가능), xlsm/docm/pptm(VBA 매크로),
            //           실행 파일·스크립트(exe, bat, sh, php, js 등)
            self::BoardFile => [
                // 문서
                'pdf', 'hwp', 'hwpx', 'hwt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                'odt', 'ods', 'odp', 'rtf', 'txt', 'csv',
                // 이미지 · 디자인
                'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'heic', 'tif', 'tiff', 'ai', 'psd',
                // 압축
                'zip', '7z', 'rar',
                // 미디어
                'mp4', 'mp3',
            ],
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
