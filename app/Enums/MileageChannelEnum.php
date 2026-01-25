<?php

namespace App\Enums;

enum MileageChannelEnum: string
{
    case Admin = 'ADMIN';       // 관리자 페이지
    case Member = 'MEMBER';     // 회원 직접
    case System = 'SYSTEM';     // 시스템 배치
}