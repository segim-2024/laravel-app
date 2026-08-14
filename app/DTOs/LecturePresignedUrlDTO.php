<?php

namespace App\DTOs;

use Carbon\CarbonInterface;

class LecturePresignedUrlDTO
{
    /**
     * @param  array<string, string>  $headers  업로드 PUT 요청 시 그대로 사용해야 하는 헤더
     */
    public function __construct(
        public string $uploadUrl,
        public array $headers,
        public string $key,
        public string $fileName,
        public string $publicUrl,
        public CarbonInterface $expiresAt
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'upload_url' => $this->uploadUrl,
            'headers' => $this->headers,
            'key' => $this->key,
            'file_name' => $this->fileName,
            'public_url' => $this->publicUrl,
            'expires_at' => $this->expiresAt->toIso8601String(),
        ];
    }
}
