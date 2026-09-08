<?php

namespace Tests\Unit\Domain\FinalGrade;

use App\Domain\FinalGrade\Exceptions\FinalGradeIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\FinalGradeTestHelper;

final class FinalGradeTest extends TestCase
{
    use FinalGradeTestHelper;

    public function test_create_returns_final_grade_without_id(): void
    {
        $finalGrade = $this->createFinalGrade();

        $this->assertNull($finalGrade->id());
        $this->assertSame($this->enrollmentId()->value(), $finalGrade->enrollmentId()->value());
        $this->assertSame($this->finalGradeType()->value, $finalGrade->grade()->value);
    }

    public function test_reconstruct_restores_final_grade_with_id(): void
    {
        $finalGrade = $this->reconstructFinalGrade();

        $this->assertSame($this->finalGradeId()->value(), $finalGrade->id()->value());
        $this->assertSame($this->enrollmentId()->value(), $finalGrade->enrollmentId()->value());
        $this->assertSame($this->finalGradeType()->value, $finalGrade->grade()->value);
    }

    public function test_require_id_returns_assigned_id(): void
    {
        $finalGrade = $this->reconstructFinalGrade();

        $this->assertSame($this->finalGradeId()->value(), $finalGrade->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(FinalGradeIdNotAssignedException::class);

        $this->createFinalGrade()->requireId();
    }
}
