<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\FinalGrade\FinalGradeDuplicateDetectorInterface;
use App\Application\FinalGrade\FinalGradeDuplicateTarget;
use UnitEnum;

final class SqliteFinalGradeDuplicateDetector extends AbstractSqliteDuplicateDetector implements FinalGradeDuplicateDetectorInterface
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            FinalGradeDuplicateTarget::ENROLLMENT_ID => 'final_grades.enrollment_id',
            default => null,
        };
    }
}
