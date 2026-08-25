<?php

namespace Tests\Unit\Domain\Semester;

use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Exceptions\SemesterIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\SemesterTestHelper;

final class SemesterTest extends TestCase
{
    use SemesterTestHelper;

    public function test_create_returns_semester_without_id(): void
    {
        $semester = $this->createSemester();

        $this->assertNull($semester->id());
        $this->assertSame('2026', $semester->academicYear());
        $this->assertSame(Term::FIRST, $semester->term());
    }

    public function test_reconstruct_restores_semester_with_id(): void
    {
        $semester = $this->reconstructSemester();

        $this->assertSame($this->semesterId()->value(), $semester->id()->value());
        $this->assertSame('2026', $semester->academicYear());
        $this->assertSame(Term::FIRST, $semester->term());
    }

    public function test_require_id_returns_assigned_id(): void
    {
        $semester = $this->reconstructSemester();

        $this->assertSame($this->semesterId()->value(), $semester->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(SemesterIdNotAssignedException::class);

        $this->createSemester()->requireId();
    }
}
