<?php

namespace App\Enums;

enum MemberPaymentStatusEnum:string
{
    case Ready = 'READY';
    case Done = 'DONE';
    case Aborted = 'ABORTED';
    case Canceled = 'CANCELED';
    case PartialCanceled = 'PARTIAL_CANCELED';
    case Waiting_For_Deposit = 'WAITING_FOR_DEPOSIT';
    case In_Progress = 'IN_PROGRESS';
    case Expired = 'EXPIRED';
}
