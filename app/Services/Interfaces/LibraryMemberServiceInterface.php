<?php

namespace App\Services\Interfaces;

use App\DTOs\LibraryMemberLookupDTO;
use App\Models\Interfaces\MemberInterface;

interface LibraryMemberServiceInterface
{
    /**
     * 계정과 비밀번호로 회원을 조회한다.
     *
     * @return MemberInterface|null 회원이 없거나 탈퇴/차단된 경우 null
     *
     * @throws \App\Exceptions\LibraryMemberPasswordMismatchException 비밀번호가 일치하지 않는 경우
     */
    public function find(LibraryMemberLookupDTO $DTO): ?MemberInterface;
}
