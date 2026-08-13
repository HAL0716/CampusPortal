<?php

namespace App\Application\Enrollment;

use App\Application\Authorization\EnrollmentAuthorizationServiceInterface;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\FinalGrade\FinalGrade;
use App\Domain\FinalGrade\FinalGradeRepositoryInterface;
use App\Domain\FinalGrade\FinalGradeType;
use App\Infrastructure\Authorization\Exceptions\UnauthorizedException;

final readonly class FinalizeGradeUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollments,
        private FinalGradeRepositoryInterface $finalGrades,
        private EnrollmentAuthorizationServiceInterface $auth,
    ) {}

    public function execute(FinalizeGradeCommand $command): void
    {
        $enrollment = $this->enrollments->findById($command->enrollmentId);
        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        if (! $this->auth->canManage($command->userId, $command->enrollmentId)) {
            throw new UnauthorizedException;
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
