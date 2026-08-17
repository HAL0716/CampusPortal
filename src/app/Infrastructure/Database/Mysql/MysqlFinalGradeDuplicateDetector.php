<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateDetector;
use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateTarget;
use UnitEnum;

final class MysqlFinalGradeDuplicateDetector extends AbstractMysqlDuplicateDetector implements FinalGradeDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            FinalGradeDuplicateTarget::ENROLLMENT_ID => 'final_grades_enrollment_id_unique',
            default => null,
        };
    }
}
