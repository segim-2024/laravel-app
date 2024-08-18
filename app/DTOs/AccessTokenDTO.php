<?php

namespace App\DTOs;

class AccessTokenDTO
{
    public function __construct(
        public string $accessToken,
        public string $expireAt
    ) {}
}
