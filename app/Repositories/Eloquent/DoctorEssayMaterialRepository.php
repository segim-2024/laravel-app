<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateDoctorEssayMaterialDTO;
use App\DTOs\UpdateDoctorEssayMaterialDTO;
use App\Models\DoctorEssayMaterial;
use App\Models\File;
use App\Repositories\Interfaces\DoctorEssayMaterialRepositoryInterface;
use Illuminate\Support\Str;

class DoctorEssayMaterialRepository extends BaseRepository implements DoctorEssayMaterialRepositoryInterface
{
    public function __construct(DoctorEssayMaterial $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByUUID(string $uuid): ?DoctorEssayMaterial
    {
        return DoctorEssayMaterial::where('material_uuid', '=', $uuid)
            ->with(['file'])
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function save(CreateDoctorEssayMaterialDTO $DTO, File $file): DoctorEssayMaterial
    {
        $material = new DoctorEssayMaterial();
        $material->lesson_uuid = $DTO->lessonUuid;
        $material->material_uuid = Str::orderedUuid();
        $material->title = $DTO->title;
        $material->hex_color_code = $DTO->colorCode;
        $material->bg_hex_color_code = $DTO->bgColorCode;
        $material->file_uuid = $file->uuid;
        $material->save();
        return $material->load(['file']);
    }

    /**
     * @inheritDoc
     */
    public function updateMaterial(DoctorEssayMaterial $material, UpdateDoctorEssayMaterialDTO $DTO, ?File $file = null): DoctorEssayMaterial
    {
        $material->title = $DTO->title;
        $material->hex_color_code = $DTO->colorCode;
        $material->bg_hex_color_code = $DTO->bgColorCode;
        if ($file) {
            $material->file_uuid = $file->uuid;
        }

        $material->save();
        return $material->load(['file']);
    }
}
