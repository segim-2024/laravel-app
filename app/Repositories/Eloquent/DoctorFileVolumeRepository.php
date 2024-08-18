<?php
namespace App\Repositories\Eloquent;

use App\DTOs\UpdateDoctorFileVolumeDescriptionDTO;
use App\DTOs\UpdateDoctorFileVolumeIsPublishedDTO;
use App\Models\DoctorFileVolume;
use App\Models\File;
use App\Repositories\Interfaces\DoctorFileVolumeRepositoryInterface;

class DoctorFileVolumeRepository extends BaseRepository implements DoctorFileVolumeRepositoryInterface
{
    public function __construct(DoctorFileVolume $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileVolume
    {
        return DoctorFileVolume::where('volume_uuid', '=', $uuid)
            ->with([
                'poster',
                'lessons' => [
                    'materials' => [
                        'file'
                    ]
                ]
            ])
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function updatePoster(DoctorFileVolume $volume, File $file): DoctorFileVolume
    {
        $volume->poster_image_uuid = $file->uuid;
        $volume->save();
        return $volume->refresh();
    }

    /**
     * @inheritDoc
     */
    public function updateDescription(UpdateDoctorFileVolumeDescriptionDTO $DTO): DoctorFileVolume
    {
        $volume = $DTO->volume;
        $volume->description = $DTO->description;
        $volume->save();
        return $volume;
    }

    /**
     * @inheritDoc
     */
    public function updateIsPublished(UpdateDoctorFileVolumeIsPublishedDTO $DTO): DoctorFileVolume
    {
        $volume = $DTO->volume;
        $volume->is_published = $DTO->isPublished;
        $volume->save();
        return $volume;
    }
}
