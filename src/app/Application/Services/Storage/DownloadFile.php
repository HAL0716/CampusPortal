<?php

namespace App\Application\Services\Storage;

final readonly class DownloadFile
{
    public function __construct(
        public string $path,
        public string $fileName,
    ) {}
}
