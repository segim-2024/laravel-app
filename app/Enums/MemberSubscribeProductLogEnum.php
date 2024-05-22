<?php

namespace App\Enums;

enum MemberSubscribeProductLogEnum:string
{
    case Subscribed = 'subscribed';
    case Unsubscribed = 'unsubscribed';
    case Started = 'started';
    case Activated = 'activated';
    case Deactivated = 'deactivated';
    case Payment = 'payment';
}
