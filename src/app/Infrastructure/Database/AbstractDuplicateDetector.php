<?php

namespace App\Infrastructure\Database;

use App\Application\Database\DuplicateDetectorInterface;
use Illuminate\Database\QueryException;
use UnitEnum;

abstract class AbstractDuplicateDetector implements DuplicateDetectorInterface
{
    public function isDuplicate(
        QueryException $e,
        UnitEnum ...$targets,
    ): bool {
        if (($e->errorInfo[1] ?? null) !== $this->duplicateErrorCode()) {
            return false;
        }

        if ($targets === []) {
            return true;
        }

        $message = $e->errorInfo[2] ?? '';

        foreach ($targets as $target) {
            $constraint = $this->constraint($target);

            if ($constraint !== null && str_contains($message, $constraint)) {
                return true;
            }
        }

        return false;
    }

    abstract protected function duplicateErrorCode(): int;

    abstract protected function constraint(UnitEnum $target): ?string;
}
