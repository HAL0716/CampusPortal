<?php

namespace App\Application\Contexts\Enrollment\UseCases;

use App\Application\Contexts\Enrollment\Commands\FinalizeGradeCommand;
use App\Application\Exceptions\ForbiddenException;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\FinalGrade\Entities\FinalGrade;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\FinalGrade\Repositories\FinalGradeRepository;

final readonly class FinalizeGradeUseCase
{
    public function __construct(
        private EnrollmentRepository $enrollments,
        private FinalGradeRepository $finalGrades,
        private CourseOfferingAuthorizationService $auth,
    ) {}

    public function execute(FinalizeGradeCommand $command): void
    {
        $enrollment = $this->enrollments->getById($command->enrollmentId);

        if (! $this->auth->canManage($command->userId, $enrollment->courseOfferingId())) {
            throw new ForbiddenException;
        }

        $this->finalGrades->save(
            FinalGrade::create(
                $command->enrollmentId,
                $command->grade,
            ),
        );

        $this->enrollments->save(
            $command->grade === FinalGradeType::F ? $enrollment->fail() : $enrollment->complete()
        );
    }
}
