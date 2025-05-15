<?php
namespace App\Repositories\Eloquent;

use App\Models\DoctorEssayVolume;
use App\Repositories\Interfaces\DoctorEssayVolumeRepositoryInterface;

class DoctorEssayVolumeRepository extends BaseRepository implements DoctorEssayVolumeRepositoryInterface
{
    public function __construct(DoctorEssayVolume $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorEssayVolume
    {
        return DoctorEssayVolume::where('volume_uuid', '=', $uuid)
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
    public function updatePoster(DoctorEssayVolume $volume, string $fileUuid): DoctorEssayVolume
    {
        $volume->poster_image_uuid = $fileUuid;
        $volume->save();
        return $volume->refresh();
    }

    /**
     * @inheritDoc
     */
    public function updateDescription(DoctorEssayVolume $volume, string $description): DoctorEssayVolume
    {
        $volume->description = $description;
        $volume->save();
        return $volume;
    }

    /**
     * @inheritDoc
     */
    public function updateIsPublished(DoctorEssayVolume $volume, bool $isPublished): DoctorEssayVolume
    {
        $volume->is_published = $isPublished;
        $volume->save();
        return $volume;
    }

    /**
     * @inheritDoc
     */
    public function updateUrl(DoctorEssayVolume $volume, string $url): DoctorEssayVolume
    {
        $volume->url = $url;
        $volume->save();
        return $volume;
    }
}
