<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateS3PresignedUrlDTO;
use App\DTOs\DeleteS3FileDTO;
use App\DTOs\S3PresignedUrlDTO;

interface S3FileServiceInterface
{
    /**
     * 업로드용 presigned PUT URL 발급
     */
    public function createUploadUrl(CreateS3PresignedUrlDTO $DTO): S3PresignedUrlDTO;

    /**
     * S3 객체 삭제
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException 대상 객체가 없는 경우
     */
    public function delete(DeleteS3FileDTO $DTO): void;
}
