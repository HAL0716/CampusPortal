<?php

namespace App\Application\CourseOffering;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Domain\CourseOffering\CourseOfferingRepositoryInterface;
use App\Domain\Student\StudentRepositoryInterface;

class ListCourseOfferingUseCase
{
    public function __construct(
        private CourseOfferingRepositoryInterface $courseOfferings,
        private StudentRepositoryInterface $students,
        private AuthenticationServiceInterface $auth,
    ) {}

    /**
     * @return CourseOfferingListDTO[]
     */
    public function execute(): array
    {
        $user = $this->auth->user();

        $studentId = null;

        if ($user !== null) {
            $student = $this->students->findByUserId($user->requireId());

            $studentId = $student?->requireId();
        }

        return array_map(
            fn (array $courseOffering) => CourseOfferingListDTO::fromArray($courseOffering),
            $studentId !== null ? $this->courseOfferings->findAllForStudent($studentId) : $this->courseOfferings->findAll()
        );
    }
}
