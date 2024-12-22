<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CreateDoctorEssayMaterialDTO;
use App\DTOs\UpdateDoctorEssayMaterialDTO;
use App\Models\DoctorEssayMaterial;
use App\Models\File;

interface DoctorEssayMaterialRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $uuid
     * @return DoctorEssayMaterial|null
     */
    public function findByUUID(string $uuid): ?DoctorEssayMaterial;

    /**
     * @param CreateDoctorEssayMaterialDTO $DTO
     * @param File $file
     * @return DoctorEssayMaterial
     */
    public function save(CreateDoctorEssayMaterialDTO $DTO, File $file): DoctorEssayMaterial;

    /**
     * @param DoctorEssayMaterial $material
     * @param UpdateDoctorEssayMaterialDTO $DTO
     * @param File|null $file
     * @return DoctorEssayMaterial
     */
    public function updateMaterial(DoctorEssayMaterial $material, UpdateDoctorEssayMaterialDTO $DTO, ?File $file = null): DoctorEssayMaterial;
}
