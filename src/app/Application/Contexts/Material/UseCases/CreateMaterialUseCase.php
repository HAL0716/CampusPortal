<?php

namespace App\Application\Contexts\Material\UseCases;

use App\Application\Contexts\Material\Commands\CreateMaterialCommand;
use App\Application\Exceptions\ForbiddenException;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;

final class CreateMaterialUseCase
{
    public function __construct(
        private CourseOfferingAuthorizationService $authorizationService,
    ) {}

    public function execute(CreateMaterialCommand $command): void
    {
        if (! $this->authorizationService->canManage($command->userId, $command->courseOfferingId)) {
            throw new ForbiddenException;
        }
    }
}
