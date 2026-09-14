<?php

namespace Tests\Unit\Domain\Semester;

use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Exceptions\SemesterIdNotAssignedException;
use App\Domain\Semester\ValueObjects\AcademicYear;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\SemesterTestHelper;

final class SemesterTest extends TestCase
{
    use SemesterTestHelper;

    public function test_create_returns_semester_without_id(): void
    {
        $semester = $this->createSemester();

        $this->assertNull($semester->id());
        $this->assertSame('2026', $semester->academicYear()->value());
        $this->assertSame(Term::FIRST, $semester->term());
        $this->assertSame('2026-01-01', $semester->startDate()->format('Y-m-d'));
        $this->assertSame('2026-03-31', $semester->endDate()->format('Y-m-d'));
    }

    public function test_reconstruct_restores_semester_with_id(): void
    {
        $semester = $this->reconstructSemester();

        $this->assertSame($this->semesterId()->value(), $semester->id()->value());
        $this->assertSame('2026', $semester->academicYear()->value());
        $this->assertSame(Term::FIRST, $semester->term());
        $this->assertSame('2026-01-01', $semester->startDate()->format('Y-m-d'));
        $this->assertSame('2026-03-31', $semester->endDate()->format('Y-m-d'));
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

    public function test_next_semester_advances_term(): void
    {
        $semester = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-03-31'),
        );

        $nextSemester = $semester->nextSemester(new DateTimeImmutable('2026-06-30'));

        $this->assertSame('2026', $nextSemester->academicYear()->value());
        $this->assertSame(Term::SECOND, $nextSemester->term());
        $this->assertSame('2026-04-01', $nextSemester->startDate()->format('Y-m-d'));
        $this->assertSame('2026-06-30', $nextSemester->endDate()->format('Y-m-d'));
        $this->assertNull($nextSemester->id());
    }

    public function test_next_semester_advances_academic_year_after_third_term(): void
    {
        $semester = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::THIRD,
            startDate: new DateTimeImmutable('2027-01-01'),
            endDate: new DateTimeImmutable('2027-03-31'),
        );

        $nextSemester = $semester->nextSemester(new DateTimeImmutable('2027-06-30'));

        $this->assertSame('2027', $nextSemester->academicYear()->value());
        $this->assertSame(Term::FIRST, $nextSemester->term());
        $this->assertSame('2027-04-01', $nextSemester->startDate()->format('Y-m-d'));
        $this->assertSame('2027-06-30', $nextSemester->endDate()->format('Y-m-d'));
        $this->assertNull($nextSemester->id());
    }
}
