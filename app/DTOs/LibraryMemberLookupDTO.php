<?php

namespace App\DTOs;

use App\Enums\MemberTargetEnum;
use App\Http\Requests\ShowLibraryMemberRequest;

class LibraryMemberLookupDTO
{
    public function __construct(
        public MemberTargetEnum $target,
        public string $account,
        public string $password
    ) {}

    /**
     * 라우트 파라미터와 HTTP Basic 인증 헤더로 DTO를 만든다.
     *
     * target 은 라우트에서 whereIn 으로 제약되므로 항상 유효한 값이다.
     */
    public static function createFromRequest(ShowLibraryMemberRequest $request): self
    {
        return new self(
            MemberTargetEnum::from((string) $request->route('target')),
            (string) $request->getUser(),
            (string) $request->getPassword()
        );
    }
}
