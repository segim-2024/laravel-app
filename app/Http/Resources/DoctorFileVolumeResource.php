<?php

namespace App\Http\Resources;

use App\Models\DoctorFileVolume;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DoctorFileVolume */
class DoctorFileVolumeResource extends JsonResource
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
            'url'          => $this->url,
            'poster'       => $this->whenLoaded('poster', fn() => new FileResource($this->poster)),
            'lessons'      => DoctorFileLessonResource::collection($this->lessons->sortBy('sort')),
        ];
    }
}
