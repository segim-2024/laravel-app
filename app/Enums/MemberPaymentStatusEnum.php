<?php

namespace App\Enums;

enum MemberPaymentStatusEnum:string
{
    case Unpaid = 'UNPAID';
    case Ready = 'READY';
    case Paid = 'PAID';
    case Failed = 'FAILED';
    case Cancelled = 'CANCELLED';
    case PartialCancelled = 'PARTIAL_CANCELLED';
}
