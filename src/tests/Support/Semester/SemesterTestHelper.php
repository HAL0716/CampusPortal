<?php

namespace Tests\Support\Semester;

use App\Domain\Academic\Term;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use Carbon\CarbonImmutable;
use Closure;
use Mockery\MockInterface;

trait SemesterTestHelper
{
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

    private function date(string $date = '2025-04-01'): CarbonImmutable
    {
        return CarbonImmutable::parse($date);
    }

    private function expectSemester(
        SemesterRepositoryInterface&MockInterface $semesters,
        ?Semester $semester,
        ?CarbonImmutable $date = null,
    ): void {
        $semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs($this->dateMatcher($date ?? $this->date()))
            ->andReturn($semester);
    }

    private function semesterIdMatcher(SemesterId $expected): Closure
    {
        return fn (SemesterId $id) => $id->value() === $expected->value();
    }

    private function dateMatcher(CarbonImmutable $expected): Closure
    {
        return fn (CarbonImmutable $date) => $date->equalTo($expected);
    }
}
