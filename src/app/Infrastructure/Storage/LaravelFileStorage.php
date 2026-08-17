<?php

namespace App\Infrastructure\Storage;

use App\Application\Services\Storage\FileStorage;
use App\Application\Services\Storage\UploadFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class LaravelFileStorage implements FileStorage
{
    private const PUBLIC_DISK = 'public';

    public function store(UploadFile $file, string $directory): string
    {
        $path = $directory.'/'.Str::uuid().'.'.pathinfo($file->originalName, PATHINFO_EXTENSION);

        Storage::disk(self::PUBLIC_DISK)->put($path, $file->contents);

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk(self::PUBLIC_DISK)->delete($path);
    }
}
