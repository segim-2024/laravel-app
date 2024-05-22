<?php

namespace App\DTOs;

use App\Enums\MemberSubscribeProductLogEnum;
use App\Models\MemberSubscribeProduct;

class MemberSubscribeProductLogDTO
{
    public function __construct(
        public readonly MemberSubscribeProduct $subscribe,
        public readonly MemberSubscribeProductLogEnum $type,
        public readonly ?string $content,
    ) {}

    public static function subscribed(MemberSubscribeProduct $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Subscribed,
            $content
        );
    }

    public static function unsubscribed(MemberSubscribeProduct $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Unsubscribed,
            $content
        );
    }

    public static function started(MemberSubscribeProduct $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Started,
            $content
        );
    }

    public static function activated(MemberSubscribeProduct $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Activated,
            $content
        );
    }

    public static function deactivated(MemberSubscribeProduct $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Deactivated,
            $content
        );
    }

    public static function payment(MemberSubscribeProduct $subscribe, ?string $content = null): MemberSubscribeProductLogDTO
    {
        return new MemberSubscribeProductLogDTO(
            $subscribe,
            MemberSubscribeProductLogEnum::Payment,
            $content
        );
    }
}
