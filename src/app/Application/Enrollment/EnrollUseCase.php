<?php

namespace App\Application\Enrollment;

use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;

final class EnrollUseCase
{
    public function __construct(
        private StudentRepository $students,
        private EnrollmentRepositoryInterface $enrollments
    ) {}

    public function execute(EnrollCommand $command): Enrollment
    {
        $student = $this->students->findByUserId($command->userId);
        if ($student === null) {
            throw new StudentNotFoundException;
        }

        // 再履修 or 新規作成
        $enrollment = $this->enrollments->find(
            $student->requireId(),
            $command->courseOfferingId,
        ) ?? Enrollment::create(
            $student->requireId(),
            $command->courseOfferingId,
        );

        return $this->enrollments->save(
            $enrollment->enroll()
        );
    }
}
