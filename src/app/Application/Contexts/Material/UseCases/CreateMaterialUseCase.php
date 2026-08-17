<?php

namespace App\Application\Contexts\Material\UseCases;

use App\Application\Contexts\Material\Commands\CreateMaterialCommand;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;
use App\Application\Services\Storage\FileStorage;
use App\Domain\Material\Entities\Material;
use App\Domain\Material\Repositories\MaterialRepository;
use App\Infrastructure\Authorization\Exceptions\UnauthorizedException;
use Throwable;

final class CreateMaterialUseCase
{
    public function __construct(
        private MaterialRepository $materials,
        private CourseOfferingAuthorizationService $authorizationService,
        private FileStorage $fileStorage,
    ) {}

    public function execute(CreateMaterialCommand $command): void
    {
        if (! $this->authorizationService->canManage($command->userId, $command->courseOfferingId)) {
            throw new UnauthorizedException;
        }

        $filePath = null;

        try {
            if ($command->file !== null) {
                $filePath = $this->fileStorage->store($command->file, 'materials');
            }

            $this->materials->save(
                Material::create(
                    courseOfferingId: $command->courseOfferingId,
                    title: $command->title,
                    description: $command->description,
                    filePath: $filePath,
                    publishDate: $command->publishDate,
                )
            );
        } catch (Throwable $e) {
            if ($filePath !== null) {
                $this->fileStorage->delete($filePath);
            }

            throw $e;
        }
    }
}
