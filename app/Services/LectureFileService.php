<?php

namespace App\Services;

use App\DTOs\CreateLecturePresignedUrlDTO;
use App\DTOs\DeleteLectureFileDTO;
use App\DTOs\LecturePresignedUrlDTO;
use App\Enums\LectureFileTypeEnum;
use App\Services\Interfaces\LectureFileServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LectureFileService implements LectureFileServiceInterface
{
    private const DISK = 's3_edu';

    private const EXPIRE_MINUTES = 30;

    /**
     * {@inheritDoc}
     */
    public function createUploadUrl(CreateLecturePresignedUrlDTO $DTO): LecturePresignedUrlDTO
    {
        $fileName = Str::orderedUuid().'.'.$DTO->extension;
        $key = $DTO->type->buildKey($fileName);
        $contentType = $DTO->type->contentType($DTO->extension);
        $expiresAt = now()->addMinutes(self::EXPIRE_MINUTES);

        $disk = Storage::disk(self::DISK);
        $presigned = $disk->temporaryUploadUrl($key, $expiresAt, ['ContentType' => $contentType]);

        return new LecturePresignedUrlDTO(
            $presigned['url'],
            ['Content-Type' => $contentType],
            $key,
            $fileName,
            $disk->url($key),
            $expiresAt
        );
    }

    /**
     * {@inheritDoc}
     */
    public function delete(DeleteLectureFileDTO $DTO): void
    {
        if (! LectureFileTypeEnum::isValidFileName($DTO->fileName)) {
            throw new AccessDeniedHttpException('삭제할 수 없는 파일명입니다.');
        }

        $key = $DTO->type->buildKey($DTO->fileName);
        $disk = Storage::disk(self::DISK);

        if (! $disk->exists($key)) {
            throw new NotFoundHttpException('파일을 찾을 수 없습니다.');
        }

        $disk->delete($key);
    }
}
