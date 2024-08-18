<?php

namespace App\Http\Resources;

use App\DTOs\AccessTokenDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AccessTokenDTO */
class AccessTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'accessToken' => $this->accessToken,
            'expireAt' => $this->expireAt,
        ];
    }
}
