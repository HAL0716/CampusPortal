<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\FinalGrade\FinalGradeDuplicateDetectorInterface;
use App\Application\Contexts\FinalGrade\FinalGradeDuplicateTarget;
use UnitEnum;

final class MysqlFinalGradeDuplicateDetector extends AbstractMysqlDuplicateDetector implements FinalGradeDuplicateDetectorInterface
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            FinalGradeDuplicateTarget::ENROLLMENT_ID => 'final_grades_enrollment_id_unique',
            default => null,
        };
    }
}
