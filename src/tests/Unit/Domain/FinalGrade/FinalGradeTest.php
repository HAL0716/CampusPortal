<?php

namespace Tests\Unit\Domain\FinalGrade;

use PHPUnit\Framework\TestCase;
use Tests\Support\FinalGrade\CreatesDomainFinalGrade;

final class FinalGradeTest extends TestCase
{
    use CreatesDomainFinalGrade;

    public function test_creates_valid_final_grade(): void
    {
        $finalGrade = $this->createFinalGrade();

        $this->assertNull($finalGrade->id());
        $this->assertSame($this->finalGradeType(), $finalGrade->grade());
    }

    public function test_reconstructs_valid_final_grade(): void
    {
        $finalGrade = $this->reconstructFinalGrade();

        $this->assertSame($this->finalGradeId(), $finalGrade->id()->value());
        $this->assertSame($this->finalGradeType(), $finalGrade->grade());
    }
}
