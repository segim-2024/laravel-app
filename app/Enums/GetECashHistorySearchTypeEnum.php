<?php

namespace App\Enums;

enum GetECashHistorySearchTypeEnum: string
{
    case ALL = 'all';
    case MEMBER_ID = 'mb_id';
    case ACADEMY_NAME = 'mb_name';
}
