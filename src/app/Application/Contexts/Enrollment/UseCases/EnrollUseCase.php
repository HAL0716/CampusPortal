<?php

namespace App\Application\Contexts\Enrollment\UseCases;

use App\Application\Contexts\Enrollment\Commands\EnrollCommand;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;

final class EnrollUseCase
{
    public function __construct(
        private StudentRepository $students,
        private EnrollmentRepository $enrollments
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
