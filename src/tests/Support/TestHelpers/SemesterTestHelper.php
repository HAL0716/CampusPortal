<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Entities\Semester;

trait SemesterTestHelper
{
    use IdTestHelper;

    protected function createSemester(
        ?string $academicYear = null,
        ?Term $term = null,
    ): Semester {
        return Semester::create(
            academicYear: $academicYear ?? '2026',
            term: $term ?? Term::FIRST,
        );
    }

    protected function reconstructSemester(
        ?int $id = null,
        ?string $academicYear = null,
        ?Term $term = null,
    ): Semester {
        return Semester::reconstruct(
            id: $this->semesterId($id),
            academicYear: $academicYear ?? '2026',
            term: $term ?? Term::FIRST,
        );
    }
}
