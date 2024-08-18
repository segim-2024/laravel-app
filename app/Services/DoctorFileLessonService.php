<?php

namespace App\Services;

use App\Models\DoctorFileLesson;
use App\Models\DoctorFileLessonMaterial;
use App\Repositories\Interfaces\DoctorFileLessonRepositoryInterface;
use App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface;
use App\Services\Interfaces\DoctorFileLessonServiceInterface;

class DoctorFileLessonService implements DoctorFileLessonServiceInterface {
    public function __construct(
        protected DoctorFileLessonMaterialServiceInterface $materialService,
        protected DoctorFileLessonRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileLesson
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function delete(DoctorFileLesson $lesson): void
    {
        $lesson->materials->each(fn(DoctorFileLessonMaterial $material) => $this->materialService->delete($material));
        $this->repository->delete($lesson);
    }
}
