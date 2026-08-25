<?php

namespace Tests\Support\TestHelpers;

use App\Domain\FinalGrade\Entities\FinalGrade;
use App\Domain\FinalGrade\Enums\FinalGradeType;

trait FinalGradeTestHelper
{
    use IdTestHelper;

    protected function finalGradeType(?FinalGradeType $grade = null): FinalGradeType
    {
        return $grade ?? FinalGradeType::A;
    }

    protected function createFinalGrade(
        ?int $enrollmentId = null,
        ?FinalGradeType $grade = null,
    ): FinalGrade {
        return FinalGrade::create(
            enrollmentId: $this->enrollmentId($enrollmentId),
            grade: $this->finalGradeType($grade),
        );
    }

    protected function reconstructFinalGrade(
        ?int $id = null,
        ?int $enrollmentId = null,
        ?FinalGradeType $grade = null,
    ): FinalGrade {
        return FinalGrade::reconstruct(
            id: $this->finalGradeId($id),
            enrollmentId: $this->enrollmentId($enrollmentId),
            grade: $this->finalGradeType($grade),
        );
    }
}
