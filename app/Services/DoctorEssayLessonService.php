<?php

namespace App\Services;

use App\Models\DoctorEssayLesson;
use App\Models\DoctorEssayMaterial;
use App\Repositories\Interfaces\DoctorEssayLessonRepositoryInterface;
use App\Services\Interfaces\DoctorEssayLessonServiceInterface;
use App\Services\Interfaces\DoctorEssayMaterialServiceInterface;

class DoctorEssayLessonService implements DoctorEssayLessonServiceInterface {
    public function __construct(
        protected DoctorEssayMaterialServiceInterface $materialService,
        protected DoctorEssayLessonRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorEssayLesson
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uuid): void
    {
        $lesson = $this->find($uuid);
        if ($lesson) {
            $lesson->materials
                ->each(fn(DoctorEssayMaterial $material) => $this->materialService->delete($material->material_uuid));

            $this->repository->delete($lesson);
        }
    }
}
