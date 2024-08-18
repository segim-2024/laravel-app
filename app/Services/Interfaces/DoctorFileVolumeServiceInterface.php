<?php

namespace App\Services\Interfaces;

use App\DTOs\UpdateDoctorFileVolumeDescriptionDTO;
use App\DTOs\UpdateDoctorFileVolumeIsPublishedDTO;
use App\DTOs\UpdateDoctorFileVolumePosterDTO;
use App\Models\DoctorFileVolume;

interface DoctorFileVolumeServiceInterface
{
    /**
     * @param string $uuid
     * @return DoctorFileVolume|null
     */
    public function find(string $uuid): ?DoctorFileVolume;

    /**
     * @param UpdateDoctorFileVolumePosterDTO $DTO
     * @return DoctorFileVolume
     */
    public function updatePoster(UpdateDoctorFileVolumePosterDTO $DTO): DoctorFileVolume;

    /**
     * @param UpdateDoctorFileVolumeDescriptionDTO $DTO
     * @return DoctorFileVolume
     */
    public function updateDescription(UpdateDoctorFileVolumeDescriptionDTO $DTO): DoctorFileVolume;

    /**
     * @param UpdateDoctorFileVolumeIsPublishedDTO $DTO
     * @return DoctorFileVolume
     */
    public function updateIsPublished(UpdateDoctorFileVolumeIsPublishedDTO $DTO): DoctorFileVolume;

    /**
     * @param DoctorFileVolume $volume
     * @return void
     */
    public function delete(DoctorFileVolume $volume): void;
}
