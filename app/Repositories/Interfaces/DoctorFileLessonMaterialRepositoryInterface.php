<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CreateDoctorFileLessonMaterialDTO;
use App\Models\DoctorFileLessonMaterial;
use App\Models\File;

interface DoctorFileLessonMaterialRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $uuid
     * @return DoctorFileLessonMaterial|null
     */
    public function findByUUID(string $uuid): ?DoctorFileLessonMaterial;

    /**
     * @param CreateDoctorFileLessonMaterialDTO $DTO
     * @param File|null $file
     * @return DoctorFileLessonMaterial
     */
    public function save(CreateDoctorFileLessonMaterialDTO $DTO, ?File $file = null): DoctorFileLessonMaterial;
}
