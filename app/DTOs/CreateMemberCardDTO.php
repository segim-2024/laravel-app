<?php

namespace App\DTOs;

use App\Http\Requests\CreateMemberCardRequest;
use App\Models\Member;

class CreateMemberCardDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly string $key,
    ) {}

    public static function createFromRequest(CreateMemberCardRequest $request):self
    {
        return new self(
            $request->user(),
            $request->validated('billing_key'),
        );
    }
}
