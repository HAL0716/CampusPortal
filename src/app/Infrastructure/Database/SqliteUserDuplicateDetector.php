<?php

namespace App\Infrastructure\Database;

use App\Application\User\UserDuplicateDetectorInterface;
use Illuminate\Database\QueryException;

final class SqliteUserDuplicateDetector implements UserDuplicateDetectorInterface
{
    public function __construct(
        private readonly array $columns,
    ) {}

    public function isDuplicate(QueryException $e): bool
    {
        return $this->hasColumn($e);
    }

    private function hasColumn(QueryException $e): bool
    {
        $message = $e->getMessage();

        foreach ($this->columns as $column) {
            if (str_contains($message, $column)) {
                return true;
            }
        }

        return false;
    }
}
