<?php

namespace App\Enums;

enum GetECashHistorySearchTypeEnum: string
{
    case ALL = 'all';
    case MEMBER_ID = 'member_id';
    case ACADEMY_NAME = 'academy_name';
}
