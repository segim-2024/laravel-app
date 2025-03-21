<?php

namespace App\Http\Resources;

use App\Models\MemberCard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MemberCard */
class AdminMemberCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nickname'   => $this->member->mb_nick,
            'member_id'  => $this->member_id,
            'name'       => $this->name,
            'number'     => $this->number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
