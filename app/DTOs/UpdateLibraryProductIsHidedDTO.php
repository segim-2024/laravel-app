<?php

namespace App\DTOs;

class UpdateLibraryProductIsHidedDTO
{
    public function __construct(
        public readonly int $id,
        public readonly bool $isHided
    ) {}

    public static function createFromRequest(): self
    {
        return new self(

        );
    }

    public function toModelAttribute(): array
    {
        return [
            'is_hided' => $this->isHided
        ];
    }
}
