<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\ValueObjects\AcademicYear;
use DateTimeImmutable;

trait SemesterTestHelper
{
    use IdTestHelper;

    protected function createSemester(
        ?AcademicYear $academicYear = null,
        ?Term $term = null,
        ?DateTimeImmutable $startDate = null,
        ?DateTimeImmutable $endDate = null,
    ): Semester {
        return Semester::create(
            academicYear: $academicYear ?? new AcademicYear('2026'),
            term: $term ?? Term::FIRST,
            startDate: $startDate ?? new DateTimeImmutable('2026-01-01'),
            endDate: $endDate ?? new DateTimeImmutable('2026-03-31')
        );
    }

    protected function reconstructSemester(
        ?int $id = null,
        ?AcademicYear $academicYear = null,
        ?Term $term = null,
        ?DateTimeImmutable $startDate = null,
        ?DateTimeImmutable $endDate = null,
    ): Semester {
        return Semester::reconstruct(
            id: $this->semesterId($id),
            academicYear: $academicYear ?? new AcademicYear('2026'),
            term: $term ?? Term::FIRST,
            startDate: $startDate ?? new DateTimeImmutable('2026-01-01'),
            endDate: $endDate ?? new DateTimeImmutable('2026-03-31')
        );
    }
}
