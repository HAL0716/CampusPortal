<?php

namespace App\Application\Services\Storage;

final readonly class UploadFile
{
    public function __construct(
        public string $originalName,
        public string $mimeType,
        public int $size,
        public string $contents,
    ) {}
}
