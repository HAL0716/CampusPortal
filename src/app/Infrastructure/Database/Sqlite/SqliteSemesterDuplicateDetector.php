<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateDetector;
use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateTarget;
use UnitEnum;

final class SqliteSemesterDuplicateDetector extends AbstractSqliteDuplicateDetector implements SemesterDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            SemesterDuplicateTarget::ACADEMIC_YEAR_AND_TERM => 'semesters.academic_year, semesters.term',
            default => null,
        };
    }
}
