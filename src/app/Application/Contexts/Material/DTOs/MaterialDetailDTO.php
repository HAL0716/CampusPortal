<?php

namespace App\Application\Contexts\Material\DTOs;

final readonly class MaterialDetailDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public ?string $filePath,
    ) {}
}
