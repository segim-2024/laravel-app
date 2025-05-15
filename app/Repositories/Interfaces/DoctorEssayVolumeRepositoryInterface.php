<?php
namespace App\Repositories\Interfaces;

use App\Models\DoctorEssayVolume;

interface DoctorEssayVolumeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $uuid
     * @return DoctorEssayVolume|null
     */
    public function find(string $uuid): ?DoctorEssayVolume;

    /**
     * @param DoctorEssayVolume $volume
     * @param string $fileUuid
     * @return DoctorEssayVolume
     */
    public function updatePoster(DoctorEssayVolume $volume, string $fileUuid): DoctorEssayVolume;

    /**
     * @param DoctorEssayVolume $volume
     * @param string $description
     * @return DoctorEssayVolume
     */
    public function updateDescription(DoctorEssayVolume $volume, string $description): DoctorEssayVolume;

    /**
     * @param DoctorEssayVolume $volume
     * @param bool $isPublished
     * @return DoctorEssayVolume
     */
    public function updateIsPublished(DoctorEssayVolume $volume, bool $isPublished): DoctorEssayVolume;

    /**
     * @param DoctorEssayVolume $volume
     * @param string $url
     * @return DoctorEssayVolume
     */
    public function updateUrl(DoctorEssayVolume $volume, string $url): DoctorEssayVolume;
}
