<?php

namespace App\Application\Enrollment;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;

final class DropEnrollmentUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollments,
        private StudentRepositoryInterface $students,
        private AuthenticationServiceInterface $auth,
    ) {}

    public function execute(DropEnrollmentCommand $command): void
    {
        $user = $this->auth->user();
        if ($user === null) {
            throw new UserNotFoundException;
        }

        $student = $this->students->findByUserId($user->requireId());
        if ($student === null) {
            throw new StudentNotFoundException;
        }

        $enrollment = $this->enrollments->find(
            $student->requireId(),
            new CourseOfferingId($command->courseOfferingId),
        );

        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        $this->enrollments->save($enrollment->drop());
    }
}
