<?php

namespace App\Application\Contexts\Enrollment\Commands;

use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\User\ValueObjects\UserId;

final readonly class FinalizeGradeCommand
{
    public function __construct(
        public UserId $userId,
        public EnrollmentId $enrollmentId,
        public FinalGradeType $grade
    ) {}
}
