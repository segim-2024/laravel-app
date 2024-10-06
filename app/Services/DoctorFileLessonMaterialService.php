<?php

namespace App\Services;

use App\DTOs\CreateDoctorFileLessonMaterialDTO;
use App\DTOs\CreateFileDTO;
use App\DTOs\UpdateDoctorFileLessonMaterialDTO;
use App\Jobs\CreateDoctorFileLessonZipJob;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\DoctorFileLessonMaterial;
use App\Repositories\Interfaces\DoctorFileLessonMaterialRepositoryInterface;
use App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface;
use App\Services\Interfaces\FileServiceInterface;

class DoctorFileLessonMaterialService implements DoctorFileLessonMaterialServiceInterface {
    public function __construct(
        protected FileServiceInterface $fileService,
        protected DoctorFileLessonMaterialRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function findByUUID(string $uuid): ?DoctorFileLessonMaterial
    {
        return $this->repository->findByUUID($uuid);
    }

    /**
     * @inheritDoc
     */
    public function create(CreateDoctorFileLessonMaterialDTO $DTO): DoctorFileLessonMaterial
    {
        $file = $this->fileService->create(CreateFileDTO::createFromDoctorFileLessonMaterial($DTO->lesson, $DTO->file));
        CreateDoctorFileLessonZipJob::dispatch($DTO->lesson)->afterCommit();
        return $this->repository->save($DTO, $file);
    }

    /**
     * @inheritDoc
     */
    public function update(UpdateDoctorFileLessonMaterialDTO $DTO): DoctorFileLessonMaterial
    {
        if ($DTO->material->file && $DTO->file) {
            DeleteFileFromS3Job::dispatch($DTO->material->file)->afterCommit();
        }

        $file = null;
        if ($DTO->file) {
            $file = $this->fileService->create(CreateFileDTO::createFromDoctorFileLessonMaterial($DTO->material->lesson, $DTO->file));
        }

        CreateDoctorFileLessonZipJob::dispatch($DTO->material->lesson)->afterCommit();
        return $this->repository->updateMaterial($DTO, $file);
    }

    /**
     * @inheritDoc
     */
    public function delete(DoctorFileLessonMaterial $material): void
    {
        if ($material->file) {
            DeleteFileFromS3Job::dispatch($material->file)->afterCommit();
        }

        $this->repository->delete($material);

        if ($material->lesson->materials()->count() < 2) {
            $this->fileService->delete($material->lesson->zip);
        }
    }
}
