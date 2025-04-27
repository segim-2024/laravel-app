<?php

namespace App\Enums;

enum SegimTicketMinusTypeEnum:string
{
    case Return = 'return';
    case Cancel = 'cancel';

    public function isReturn(): bool
    {
        return $this === self::Return;
    }
}
