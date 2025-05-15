<?php

namespace App\Services\Interfaces;

use App\DTOs\UpdateDoctorEssayVolumeDescriptionDTO;
use App\DTOs\UpdateDoctorEssayVolumeIsPublishedDTO;
use App\DTOs\UpdateDoctorEssayVolumePosterDTO;
use App\DTOs\UpdateDoctorEssayVolumeUrlDTO;
use App\Exceptions\DoctorEssayVolumeNotFoundException;
use App\Models\DoctorEssayVolume;

interface DoctorEssayVolumeServiceInterface
{
    /**
     * @param string $uuid
     * @return DoctorEssayVolume|null
     */
    public function find(string $uuid): ?DoctorEssayVolume;

    /**
     * @param UpdateDoctorEssayVolumePosterDTO $DTO
     * @return DoctorEssayVolume
     * @throws DoctorEssayVolumeNotFoundException
     */
    public function updatePoster(UpdateDoctorEssayVolumePosterDTO $DTO): DoctorEssayVolume;

    /**
     * @param UpdateDoctorEssayVolumeDescriptionDTO $DTO
     * @return DoctorEssayVolume
     * @throws DoctorEssayVolumeNotFoundException
     */
    public function updateDescription(UpdateDoctorEssayVolumeDescriptionDTO $DTO): DoctorEssayVolume;

    /**
     * @param UpdateDoctorEssayVolumeUrlDTO $DTO
     * @return DoctorEssayVolume
     * @throws DoctorEssayVolumeNotFoundException
     */
    public function updateUrl(UpdateDoctorEssayVolumeUrlDTO $DTO): DoctorEssayVolume;

    /**
     * @param UpdateDoctorEssayVolumeIsPublishedDTO $DTO
     * @return DoctorEssayVolume
     * @throws DoctorEssayVolumeNotFoundException
     */
    public function updateIsPublished(UpdateDoctorEssayVolumeIsPublishedDTO $DTO): DoctorEssayVolume;

    /**
     * @param string $uuid
     * @return void
     */
    public function delete(string $uuid): void;
}
