<?php

namespace Tests\Unit\Domain\Semester;

use App\Domain\Academic\Term;
use App\Domain\Semester\Exceptions\SemesterIdNotAssignedException;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use PHPUnit\Framework\TestCase;

final class SemesterTest extends TestCase
{
    public function test_create_returns_unassigned_semester(): void
    {
        $semester = Semester::create('2025', Term::FIRST);

        self::assertNull($semester->id());
        self::assertSame('2025', $semester->academicYear());
        self::assertSame(Term::FIRST, $semester->term());
    }

    public function test_reconstruct_restores_semester_state(): void
    {
        $semester = $this->semester();

        self::assertSame(1, $semester->requireId()->value());
        self::assertSame('2025', $semester->academicYear());
        self::assertSame(Term::FIRST, $semester->term());
    }

    public function test_require_id_fails_when_semester_is_not_persisted(): void
    {
        $this->expectException(SemesterIdNotAssignedException::class);

        Semester::create('2025', Term::FIRST)->requireId();
    }

    private function semester(
        int $id = 1,
        string $academicYear = '2025',
        Term $term = Term::FIRST,
    ): Semester {
        return Semester::reconstruct(
            id: new SemesterId($id),
            academicYear: $academicYear,
            term: $term,
        );
    }
}
