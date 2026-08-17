<?php

namespace Tests\Support\FinalGrade;

use App\Domain\FinalGrade\Enums\FinalGradeType;

trait FinalGradeTestData
{
    protected function finalGradeId(): int
    {
        return 1;
    }

    protected function enrollmentId(): int
    {
        return 1;
    }

    protected function finalGradeType(): FinalGradeType
    {
        return FinalGradeType::A;
    }
}
