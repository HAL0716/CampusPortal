<?php

namespace App\Infrastructure\Storage;

use App\Application\Services\Storage\DownloadFile;
use App\Application\Services\Storage\FileStorage;
use App\Application\Services\Storage\UploadFile;
use App\Infrastructure\Storage\Exceptions\FileStorageException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class LaravelFileStorage implements FileStorage
{
    private Filesystem $disk;

    public function __construct()
    {
        $this->disk = Storage::disk(config('filesystems.default'));
    }

    public function store(UploadFile $file, string $directory): string
    {
        $path = $this->generatePath($file, $directory);

        if (! $this->disk->put($path, $file->contents)) {
            throw new FileStorageException;
        }

        return $path;
    }

    public function exists(string $path): bool
    {
        return $this->disk->exists($path);
    }

    public function delete(string $path): void
    {
        if (! $this->exists($path) || ! $this->disk->delete($path)) {
            throw new FileStorageException;
        }
    }

    public function resolveDownload(string $path, string $fileName): DownloadFile
    {
        if (! $this->exists($path)) {
            throw new FileStorageException;
        }

        return new DownloadFile(
            path: $this->disk->path($path),
            fileName: $fileName,
        );
    }

    private function generatePath(UploadFile $file, string $directory): string
    {
        return sprintf(
            '%s/%s.%s',
            $directory,
            Str::uuid(),
            pathinfo($file->originalName, PATHINFO_EXTENSION),
        );
    }
}
