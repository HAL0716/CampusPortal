<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\Student\Duplicate\StudentDuplicateDetector;
use App\Application\Contexts\Student\Duplicate\StudentDuplicateTarget;
use UnitEnum;

final class MysqlStudentDuplicateDetector extends AbstractMysqlDuplicateDetector implements StudentDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            StudentDuplicateTarget::STUDENT_NUMBER => 'students_student_number_unique',
            default => null,
        };
    }
}
