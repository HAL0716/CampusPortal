<?php

namespace App\Application\Enrollment;

use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;

final readonly class DropUseCase
{
    public function __construct(
        private StudentRepository $students,
        private EnrollmentRepository $enrollments
    ) {}

    public function execute(DropCommand $command): Enrollment
    {
        $student = $this->students->findByUserId($command->userId);
        if ($student === null) {
            throw new StudentNotFoundException;
        }

        $enrollment = $this->enrollments->find(
            $student->requireId(),
            $command->courseOfferingId
        );
        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        return $this->enrollments->save(
            $enrollment->drop()
        );
    }
}
