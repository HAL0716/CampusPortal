<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateDetector;
use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateTarget;
use UnitEnum;

final class MysqlEnrollmentDuplicateDetector extends AbstractMysqlDuplicateDetector implements EnrollmentDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            EnrollmentDuplicateTarget::STUDENT_COURSE_OFFERING => 'enrollments_student_id_course_offering_id_unique',
            default => null,
        };
    }
}
