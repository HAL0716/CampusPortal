<?php

namespace App\Application\Services\Storage;

interface FileStorage
{
    public function store(UploadFile $file, string $directory): string;

    public function delete(string $path): void;

    public function exists(string $path): bool;

    public function resolveDownload(string $path, string $fileName): DownloadFile;
}
