<?php

namespace App\Application\Enrollment;

use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\StudentRepositoryInterface;

final class EnrollUseCase
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private EnrollmentRepositoryInterface $enrollments
    ) {}

    public function execute(EnrollCommand $command): Enrollment
    {
        $student = $this->students->findByUserId($command->userId);
        if ($student === null) {
            throw new StudentNotFoundException;
        }

        return $this->enrollments->save(
            Enrollment::create(
                $student->requireId(),
                $command->courseOfferingId
            )
        );
    }
}
