<?php

namespace Tests\Support\Semester;

use App\Domain\Academic\Term;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterRepositoryInterface;
use Carbon\CarbonImmutable;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;

trait SemesterTestHelper
{
    use IdTestHelper;
    use UseMatcher;

    private function semester(
        ?int $id = null,
        ?string $academicYear = null,
        ?Term $term = null,
    ): Semester {
        return Semester::reconstruct(
            id: $this->semesterId($id),
            academicYear: $academicYear ?? '2025',
            term: $term ?? Term::FIRST,
        );
    }

    private function date(?string $date = null): CarbonImmutable
    {
        return CarbonImmutable::parse($date ?? '2025-04-01');
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
}
