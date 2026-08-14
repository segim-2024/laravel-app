<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateLecturePresignedUrlDTO;
use App\DTOs\DeleteLectureFileDTO;
use App\DTOs\LecturePresignedUrlDTO;

interface LectureFileServiceInterface
{
    /**
     * 강의 파일 업로드용 presigned PUT URL 발급
     */
    public function createUploadUrl(CreateLecturePresignedUrlDTO $DTO): LecturePresignedUrlDTO;

    /**
     * 강의 파일 삭제
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException 대상 객체가 없는 경우
     */
    public function delete(DeleteLectureFileDTO $DTO): void;
}
