<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateDoctorFileLessonMaterialDTO;
use App\DTOs\UpdateDoctorFileLessonMaterialDTO;
use App\Models\DoctorFileLessonMaterial;
use App\Models\File;
use App\Repositories\Interfaces\DoctorFileLessonMaterialRepositoryInterface;
use Illuminate\Support\Str;

class DoctorFileLessonMaterialRepository extends BaseRepository implements DoctorFileLessonMaterialRepositoryInterface
{
    public function __construct(DoctorFileLessonMaterial $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByUUID(string $uuid): ?DoctorFileLessonMaterial
    {
        return DoctorFileLessonMaterial::where('material_uuid', '=', $uuid)
            ->with(['file'])
            ->first();
    }

    /**
     * @param CreateDoctorFileLessonMaterialDTO $DTO
     * @inheritDoc
     */
    public function save(CreateDoctorFileLessonMaterialDTO $DTO, ?File $file = null): DoctorFileLessonMaterial
    {
        $material = new DoctorFileLessonMaterial();
        $material->lesson_uuid = $DTO->lesson->lesson_uuid;
        $material->material_uuid = Str::orderedUuid();
        $material->title = $DTO->title;
        $material->description = $DTO->description;
        $material->hex_color_code = $DTO->colorCode;
        $material->file_uuid = $file?->uuid;
        $material->save();
        return $material->load(['file']);
    }

    /**
     * @inheritDoc
     */
    public function updateMaterial(UpdateDoctorFileLessonMaterialDTO $DTO, ?File $file = null): DoctorFileLessonMaterial
    {
        $material = $DTO->material;
        $material->title = $DTO->title;
        $material->description = $DTO->description;
        $material->hex_color_code = $DTO->colorCode;
        $material->file_uuid = $file?->uuid;
        $material->save();
        return $material->load(['file']);
    }
}
