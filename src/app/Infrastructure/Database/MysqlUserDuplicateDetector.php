<?php

namespace App\Infrastructure\Database;

use App\Application\User\UserDuplicateDetectorInterface;
use Illuminate\Database\QueryException;

final class MysqlUserDuplicateDetector implements UserDuplicateDetectorInterface
{
    private const MYSQL_DUPLICATE_ENTRY = 1062;

    public function __construct(
        private readonly array $constraints,
    ) {}

    public function isDuplicate(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === self::MYSQL_DUPLICATE_ENTRY
            && $this->hasConstraint($e);
    }

    private function hasConstraint(QueryException $e): bool
    {
        $message = $e->errorInfo[2] ?? '';

        foreach ($this->constraints as $constraint) {
            if (str_contains($message, $constraint)) {
                return true;
            }
        }

        return false;
    }
}
