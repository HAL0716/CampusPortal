<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateDetector;
use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateTarget;
use UnitEnum;

final class MysqlSemesterDuplicateDetector extends AbstractMysqlDuplicateDetector implements SemesterDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            SemesterDuplicateTarget::ACADEMIC_YEAR_AND_TERM => 'semesters_academic_year_term_unique',
            default => null,
        };
    }
}
