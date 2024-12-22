<?php

namespace App\Http\Resources;

use App\Models\DoctorEssayLesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DoctorEssayLesson */
class DoctorEssayLessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'series_uuid' => $this->series_uuid,
            'volume_uuid' => $this->volume_uuid,
            'lesson_uuid' => $this->lesson_uuid,
            'title'       => $this->title,
            'sort'        => $this->sort,
            'materials'   => DoctorEssayMaterialResource::collection($this->materials->sortBy('created_at')),
        ];
    }
}
