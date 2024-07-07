<?php

namespace App\DTOs;

class AlimTokDTO
{
    public function __construct(
        public string $phoneNumber,
        public string $templateCode,
        public string $message,
        public ?string $title = null,
        public ?string $header = null,
        public ?string $attachment = null,
//        public string $tran_type,
//        public string $tran_message,
    ) {}
}
