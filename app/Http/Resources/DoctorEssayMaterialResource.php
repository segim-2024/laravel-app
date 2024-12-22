<?php

namespace App\Http\Resources;

use App\Models\DoctorEssayMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DoctorEssayMaterial */
class DoctorEssayMaterialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'material_uuid'     => $this->material_uuid,
            'title'             => $this->title,
            'hex_color_code'    => $this->hex_color_code,
            'bg_hex_color_code' => $this->bg_hex_color_code,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'file'              => new FileResource($this->whenLoaded('file')),
        ];
    }
}
