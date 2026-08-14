<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateDetector;
use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateTarget;
use UnitEnum;

final class SqliteEnrollmentDuplicateDetector extends AbstractSqliteDuplicateDetector implements EnrollmentDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            EnrollmentDuplicateTarget::STUDENT_COURSE_OFFERING => 'enrollments.student_id, enrollments.course_offering_id',
            default => null,
        };
    }
}
