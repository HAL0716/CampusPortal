<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateDetector;
use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateTarget;
use UnitEnum;

final class SqliteFinalGradeDuplicateDetector extends AbstractSqliteDuplicateDetector implements FinalGradeDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            FinalGradeDuplicateTarget::ENROLLMENT_ID => 'final_grades.enrollment_id',
            default => null,
        };
    }
}
