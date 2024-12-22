<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateDoctorEssayMaterialDTO;
use App\DTOs\UpdateDoctorEssayMaterialDTO;
use App\Exceptions\DoctorEssayLessonNotFoundExeption;
use App\Exceptions\DoctorEssayMaterialNotFoundExeption;
use App\Models\DoctorEssayMaterial;

interface DoctorEssayMaterialServiceInterface
{
    /**
     * @param string $uuid
     * @return DoctorEssayMaterial|null
     */
    public function findByUUID(string $uuid): ?DoctorEssayMaterial;

    /**
     * @param CreateDoctorEssayMaterialDTO $DTO
     * @return DoctorEssayMaterial
     * @throws DoctorEssayLessonNotFoundExeption
     */
    public function create(CreateDoctorEssayMaterialDTO $DTO): DoctorEssayMaterial;

    /**
     * @param UpdateDoctorEssayMaterialDTO $DTO
     * @return DoctorEssayMaterial
     * @throws DoctorEssayMaterialNotFoundExeption
     */
    public function update(UpdateDoctorEssayMaterialDTO $DTO): DoctorEssayMaterial;

    /**
     * @param string $uuid
     * @return void
     */
    public function delete(string $uuid): void;
}
