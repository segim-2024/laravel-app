<?php

namespace App\Services;

use App\DTOs\CreateS3PresignedUrlDTO;
use App\DTOs\DeleteS3FileDTO;
use App\DTOs\S3PresignedUrlDTO;
use App\Enums\S3FileTypeEnum;
use App\Services\Interfaces\S3FileServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class S3FileService implements S3FileServiceInterface
{
    private const DISK = 's3_edu';

    private const EXPIRE_MINUTES = 30;

    /**
     * {@inheritDoc}
     */
    public function createUploadUrl(CreateS3PresignedUrlDTO $DTO): S3PresignedUrlDTO
    {
        $fileName = Str::orderedUuid().'.'.$DTO->extension;
        $key = $DTO->type->buildKey($fileName);
        $contentType = $DTO->type->contentType($DTO->extension);
        $expiresAt = now()->addMinutes(self::EXPIRE_MINUTES);

        $disk = Storage::disk(self::DISK);
        $presigned = $disk->temporaryUploadUrl($key, $expiresAt, ['ContentType' => $contentType]);

        return new S3PresignedUrlDTO(
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
    public function delete(DeleteS3FileDTO $DTO): void
    {
        if (! S3FileTypeEnum::isValidFileName($DTO->fileName)) {
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
