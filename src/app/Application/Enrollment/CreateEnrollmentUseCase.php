<?php

namespace App\Application\Enrollment;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;

class CreateEnrollmentUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $repository,
        private StudentRepositoryInterface $students,
        private AuthenticationServiceInterface $auth,
    ) {}

    public function execute(CreateEnrollmentCommand $command): Enrollment
    {
        $user = $this->auth->user();
        if ($user === null) {
            throw new UserNotFoundException;
        }

        $student = $this->students->findByUserId($user->requireId());
        if ($student === null) {
            throw new StudentNotFoundException;
        }

        return $this->repository->save(
            Enrollment::create(
                $student->requireId(),
                new CourseOfferingId($command->courseOfferingId)
            )
        );
    }
}
