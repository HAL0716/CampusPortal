<?php

namespace Tests\Support\FinalGrade;

use App\Domain\Enrollment\EnrollmentId;
use App\Domain\FinalGrade\FinalGrade;
use App\Domain\FinalGrade\FinalGradeId;
use App\Domain\FinalGrade\FinalGradeType;

trait CreatesDomainFinalGrade
{
    use FinalGradeTestData;

    protected function finalGradeIdValueObject(?int $id = null): FinalGradeId
    {
        return new FinalGradeId($id ?? $this->finalGradeId());
    }

    protected function enrollmentIdValueObject(?int $id = null): EnrollmentId
    {
        return new EnrollmentId($id ?? $this->enrollmentId());
    }

    protected function createFinalGrade(
        ?EnrollmentId $enrollmentId = null,
        ?FinalGradeType $grade = null,
    ): FinalGrade {
        return FinalGrade::create(
            enrollmentId: $this->enrollmentIdValueObject($enrollmentId),
            grade: $grade ?? $this->finalGradeType(),
        );
    }

    protected function reconstructFinalGrade(
        ?int $id = null,
        ?EnrollmentId $enrollmentId = null,
        ?FinalGradeType $grade = null,
    ): FinalGrade {
        return FinalGrade::reconstruct(
            id: $this->finalGradeIdValueObject($id),
            enrollmentId: $this->enrollmentIdValueObject($enrollmentId),
            grade: $grade ?? $this->finalGradeType(),
        );
    }
}
