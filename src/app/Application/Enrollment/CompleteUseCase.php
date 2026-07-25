<?php

namespace App\Application\Enrollment;

use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;

final readonly class CompleteUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollments
    ) {}

    public function execute(CompleteCommand $command): void
    {
        $enrollment = $this->enrollments->findById($command->enrollmentId);
        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        $this->enrollments->save(
            $enrollment->complete()
        );
    }
}
