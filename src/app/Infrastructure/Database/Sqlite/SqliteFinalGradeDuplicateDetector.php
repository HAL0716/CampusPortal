<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\FinalGrade\FinalGradeDuplicateDetectorInterface;
use App\Application\Contexts\FinalGrade\FinalGradeDuplicateTarget;
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
