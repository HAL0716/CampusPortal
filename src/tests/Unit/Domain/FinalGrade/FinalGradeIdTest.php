<?php

namespace Tests\Unit\Domain\FinalGrade;

use PHPUnit\Framework\TestCase;
use Tests\Support\FinalGrade\CreatesDomainFinalGrade;

final class FinalGradeIdTest extends TestCase
{
    use CreatesDomainFinalGrade;

    public function test_creates_valid_final_grade_id(): void
    {
        $id = $this->finalGradeIdValueObject();

        $this->assertSame($this->finalGradeId(), $id->value());
    }
}
