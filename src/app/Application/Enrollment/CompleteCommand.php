<?php

namespace App\Application\Enrollment;

use App\Domain\Enrollment\EnrollmentId;
use App\Domain\FinalGrade\FinalGradeType;
use App\Domain\User\UserId;

final readonly class CompleteCommand
{
    public function __construct(
        public UserId $userId,
        public EnrollmentId $enrollmentId,
        public FinalGradeType $grade
    ) {}
}
