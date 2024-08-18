<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin File */
class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'original_name' => $this->original_name,
            'full_path'     => $this->full_path,
            'size'          => $this->size,
        ];
    }
}
