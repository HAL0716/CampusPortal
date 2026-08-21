<?php

namespace App\Application\Contexts\Enrollment\UseCases;

use App\Application\Contexts\Enrollment\Commands\DropCommand;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Student\Repositories\StudentRepository;

final readonly class DropUseCase
{
    public function __construct(
        private StudentRepository $students,
        private EnrollmentRepository $enrollments
    ) {}

    public function execute(DropCommand $command): Enrollment
    {
        $student = $this->students->getByUserId($command->userId);

        $enrollment = $this->enrollments->getByStudentAndCourseOffering($student->requireId(), $command->courseOfferingId);

        return $this->enrollments->save(
            $enrollment->drop()
        );
    }
}
