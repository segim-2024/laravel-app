<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorFileSeriesRootResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
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
    }
}
