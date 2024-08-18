<?php
namespace App\Repositories\Interfaces;

use App\DTOs\UpdateDoctorFileVolumeDescriptionDTO;
use App\DTOs\UpdateDoctorFileVolumeIsPublishedDTO;
use App\Models\DoctorFileVolume;
use App\Models\File;

interface DoctorFileVolumeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $uuid
     * @return DoctorFileVolume|null
     */
    public function find(string $uuid): ?DoctorFileVolume;

    /**
     * @param DoctorFileVolume $volume
     * @param File $file
     * @return DoctorFileVolume
     */
    public function updatePoster(DoctorFileVolume $volume, File $file): DoctorFileVolume;

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
}
