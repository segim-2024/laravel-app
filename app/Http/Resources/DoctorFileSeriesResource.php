<?php

namespace App\Http\Resources;

use App\Models\DoctorFileSeries;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DoctorFileSeries */
class DoctorFileSeriesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'series_uuid' => $this->series_uuid,
            'title'       => $this->title,
            'sort'        => $this->sort,
            'is_whale'    => $this->is_whale,
            'volumes'     => DoctorFileVolumeResource::collection($this->volumes->sortBy('sort')),
        ];
    }
}
