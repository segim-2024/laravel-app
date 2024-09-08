<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateDoctorFileLessonMaterialDTO;
use App\DTOs\UpdateDoctorFileLessonMaterialDTO;
use App\Models\DoctorFileLessonMaterial;

interface DoctorFileLessonMaterialServiceInterface
{
    /**
     * @param string $uuid
     * @return DoctorFileLessonMaterial|null
     */
    public function findByUUID(string $uuid): ?DoctorFileLessonMaterial;

    /**
     * @param CreateDoctorFileLessonMaterialDTO $DTO
     * @return DoctorFileLessonMaterial
     */
    public function create(CreateDoctorFileLessonMaterialDTO $DTO): DoctorFileLessonMaterial;

    /**
     * @param UpdateDoctorFileLessonMaterialDTO $DTO
     * @return DoctorFileLessonMaterial
     */
    public function update(UpdateDoctorFileLessonMaterialDTO $DTO): DoctorFileLessonMaterial;

    /**
     * @param DoctorFileLessonMaterial $material
     * @return void
     */
    public function delete(DoctorFileLessonMaterial $material): void;
}
