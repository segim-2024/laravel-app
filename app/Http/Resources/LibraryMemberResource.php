<?php

namespace App\Http\Resources;

use App\Enums\MemberTargetEnum;
use App\Enums\MemberTypeEnum;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 라이브러리 서버에 전달하는 회원 정보.
 *
 * @mixin Member
 */
class LibraryMemberResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = MemberTypeEnum::fromMbType($this->mb_type);

        return [
            'account' => $this->mb_id,
            'name' => $this->mb_name,
            'level' => $type->libraryLevel(),
            'type' => $type->value,
            'target' => MemberTargetEnum::fromMember($this->resource)->value,
            'campus_account' => $this->resolveCampusAccount($type),
        ];
    }

    /**
     * 회원이 귀속된 캠퍼스 계정.
     *
     * 학원 계정은 소속(mb_4)이 전원 비어 있으므로 자기 계정을 반환한다.
     * 호출 측이 유형별로 분기하지 않아도 캠퍼스 단위 조회를 할 수 있게 하기 위함이다.
     */
    private function resolveCampusAccount(MemberTypeEnum $type): ?string
    {
        if ($type->isCampus()) {
            return $this->mb_id;
        }

        return $this->mb_4 ?: null;
    }
}
