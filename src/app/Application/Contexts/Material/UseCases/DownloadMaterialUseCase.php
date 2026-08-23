<?php

declare(strict_types=1);

namespace App\Application\Contexts\Material\UseCases;

use App\Application\Contexts\Material\Commands\DownloadMaterialCommand;
use App\Application\Services\Clock\Clock;
use App\Application\Services\Storage\DownloadFile;
use App\Application\Services\Storage\FileStorage;
use App\Domain\Material\Repositories\MaterialRepository;

final readonly class DownloadMaterialUseCase
{
    public function __construct(
        private MaterialRepository $materialRepository,
        private FileStorage $fileStorage,
        private Clock $clock,
    ) {}

    public function execute(DownloadMaterialCommand $command): DownloadFile
    {
        $filePath = $this->materialRepository
            ->getById($command->materialId)
            ->requireFilePath();

        return $this->fileStorage->resolveDownload($filePath, $this->generateFileName($filePath));
    }

    private function generateFileName(string $filePath): string
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return sprintf(
            '%s.%s',
            $this->clock->now()->format('Ymd_His'),
            $extension,
        );
    }
}
