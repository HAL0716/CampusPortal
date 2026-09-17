<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateDetector;
use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateTarget;
use UnitEnum;

final class SqliteCourseOfferingDuplicateDetector extends AbstractSqliteDuplicateDetector implements CourseOfferingDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            CourseOfferingDuplicateTarget::COURSE_SEMESTER => 'course_offerings.course_id, course_offerings.semester_id',
            default => null,
        };
    }
}
