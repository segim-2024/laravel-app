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
        $data = [];
        $index = 0;

        $data[$index] = [
            'id' => 1,
            'pId' => 0,
            'name' => '자료 박사',
            'isParent' => true,
            'isAdd' => true,
            'isEdit' => false,
            'isUpload' => false,
            'open' => true,
            'depth' => 0,
        ];

        return [
            'id'  => $this->uuid,
            'pId' => 1,
            'name' => $this->title,
            'isParent' => true,
            'isAdd' => true,
            'isEdit' => true,
            'isUpload' => false,
            'depth' => 1,
        ];
    }
}
