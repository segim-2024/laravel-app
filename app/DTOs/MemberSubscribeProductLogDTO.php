<?php

namespace App\DTOs;

use App\Enums\MemberSubscribeProductLogEnum;
use App\Models\Interfaces\SubscribeProductInterface;

class MemberSubscribeProductLogDTO
{
    public function __construct(
        public readonly SubscribeProductInterface $subscribe,
        public readonly MemberSubscribeProductLogEnum $type,
        public readonly ?string $content,
    ) {}

    public static function subscribed(SubscribeProductInterface $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Subscribed,
            $content
        );
    }

    public static function unsubscribed(SubscribeProductInterface $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Unsubscribed,
            $content
        );
    }

    public static function started(SubscribeProductInterface $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Started,
            $content
        );
    }

    public static function activated(SubscribeProductInterface $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Activated,
            $content
        );
    }

    public static function deactivated(SubscribeProductInterface $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Deactivated,
            $content
        );
    }

    public static function payment(SubscribeProductInterface $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Payment,
            $content
        );
    }
}
