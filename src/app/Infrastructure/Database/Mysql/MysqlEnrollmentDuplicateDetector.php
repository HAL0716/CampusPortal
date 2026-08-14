<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\Enrollment\EnrollmentDuplicateDetectorInterface;
use App\Application\Contexts\Enrollment\EnrollmentDuplicateTarget;
use UnitEnum;

final class MysqlEnrollmentDuplicateDetector extends AbstractMysqlDuplicateDetector implements EnrollmentDuplicateDetectorInterface
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            EnrollmentDuplicateTarget::STUDENT_COURSE_OFFERING => 'enrollments_student_id_course_offering_id_unique',
            default => null,
        };
    }
}
