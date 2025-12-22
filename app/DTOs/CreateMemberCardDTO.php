<?php

namespace App\DTOs;

use App\Http\Requests\CreateMemberCardRequest;
use App\Models\Interfaces\MemberInterface;

class CreateMemberCardDTO
{
    public function __construct(
        public readonly MemberInterface $member,
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
