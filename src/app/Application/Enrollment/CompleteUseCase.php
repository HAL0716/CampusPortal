<?php

namespace App\Application\Enrollment;

use App\Application\Authorization\EnrollmentAuthorizationServiceInterface;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Infrastructure\Authorization\Exceptions\UnauthorizedException;

final readonly class CompleteUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollments,
        private EnrollmentAuthorizationServiceInterface $auth,
    ) {}

    public function execute(CompleteCommand $command): void
    {
        $enrollment = $this->enrollments->findById($command->enrollmentId);
        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        if (! $this->auth->canManage($command->userId, $command->enrollmentId)) {
            throw new UnauthorizedException;
        }

        $this->enrollments->save(
            $enrollment->complete()
        );
    }
}
