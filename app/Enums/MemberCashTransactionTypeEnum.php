<?php

namespace App\Enums;

enum MemberCashTransactionTypeEnum:string
{
    case Increased = "increased";
    case Decreased = "decreased";
}
