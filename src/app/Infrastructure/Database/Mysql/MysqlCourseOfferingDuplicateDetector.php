<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateDetector;
use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateTarget;
use UnitEnum;

final class MysqlCourseOfferingDuplicateDetector extends AbstractMysqlDuplicateDetector implements CourseOfferingDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            CourseOfferingDuplicateTarget::COURSE_SEMESTER => 'course_offering_course_semester_unique',
            default => null,
        };
    }
}
