<?php

namespace App\Services;

use App\DTOs\CreateDoctorEssayMaterialDTO;
use App\DTOs\CreateFileDTO;
use App\DTOs\UpdateDoctorEssayMaterialDTO;
use App\Exceptions\DoctorEssayLessonNotFoundExeption;
use App\Exceptions\DoctorEssayMaterialNotFoundExeption;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\DoctorEssayMaterial;
use App\Repositories\Interfaces\DoctorEssayLessonRepositoryInterface;
use App\Repositories\Interfaces\DoctorEssayMaterialRepositoryInterface;
use App\Services\Interfaces\DoctorEssayMaterialServiceInterface;
use App\Services\Interfaces\FileServiceInterface;

class DoctorEssayMaterialService implements DoctorEssayMaterialServiceInterface {
    public function __construct(
        protected FileServiceInterface $fileService,
        protected DoctorEssayLessonRepositoryInterface $lessonRepository,
        protected DoctorEssayMaterialRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function findByUUID(string $uuid): ?DoctorEssayMaterial
    {
        return $this->repository->findByUUID($uuid);
    }

    /**
     * @inheritDoc
     */
    public function create(CreateDoctorEssayMaterialDTO $DTO): DoctorEssayMaterial
    {
        $lesson = $this->lessonRepository->find($DTO->lessonUuid);
        if (! $lesson) {
            throw new DoctorEssayLessonNotFoundExeption("해당 레슨을 찾을 수 없습니다.");
        }

        // 파일 생성
        $file = $this->fileService->create(CreateFileDTO::createFromDoctorEssayMaterial($lesson->lesson_uuid, $DTO->file));

        // 자료 생성
        return $this->repository->save($DTO, $file);
    }

    /**
     * @inheritDoc
     */
    public function update(UpdateDoctorEssayMaterialDTO $DTO): DoctorEssayMaterial
    {
        $material = $this->findByUUID($DTO->materialUuid);
        if (! $material) {
            throw new DoctorEssayMaterialNotFoundExeption("해당 자료를 찾을 수 없습니다.");
        }

        // 파일이 새로 업로드 되었을 경우에만 새로운 파일 수정
        $file = null;
        if ($DTO->file) {
            // 기존 파일 삭제
            if ($material->file) {
                DeleteFileFromS3Job::dispatch($material->file)->afterCommit();
            }

            $file = $this->fileService->create(CreateFileDTO::createFromDoctorEssayMaterial($material->lesson_uuid, $DTO->file));
        }

        return $this->repository->updateMaterial($material, $DTO, $file);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uuid): void
    {
        $material = $this->findByUUID($uuid);
        if (! $material) {
            return;
        }

        if ($material->file) {
            DeleteFileFromS3Job::dispatch($material->file)->afterCommit();
        }

        $this->repository->delete($material);
    }
}
