<?php

namespace App\Http\Resources;

use App\Models\DoctorEssayVolume;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DoctorEssayVolume */
class DoctorEssayVolumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'series_uuid'  => $this->series_uuid,
            'volume_uuid'  => $this->volume_uuid,
            'title'        => $this->title,
            'description'  => $this->description,
            'sort'         => $this->sort,
            'is_published' => $this->is_published,
            'poster'       => $this->whenLoaded('poster', fn() => new FileResource($this->poster)),
            'lessons'      => DoctorEssayLessonResource::collection($this->lessons->sortBy('sort')),
        ];
    }
}
