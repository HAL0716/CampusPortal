<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\Student\Duplicate\StudentDuplicateDetector;
use App\Application\Contexts\Student\Duplicate\StudentDuplicateTarget;
use UnitEnum;

final class SqliteStudentDuplicateDetector extends AbstractSqliteDuplicateDetector implements StudentDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            StudentDuplicateTarget::STUDENT_NUMBER => 'students.student_number',
            default => null,
        };
    }
}
