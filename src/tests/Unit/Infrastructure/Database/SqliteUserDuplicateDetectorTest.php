<?php

namespace Tests\Unit\Infrastructure\Database;

use App\Infrastructure\Database\SqliteUserDuplicateDetector;
use Exception;
use Illuminate\Database\QueryException;
use Tests\TestCase;

final class SqliteUserDuplicateDetectorTest extends TestCase
{
    private SqliteUserDuplicateDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->detector = new SqliteUserDuplicateDetector(['users.email']);
    }

    public function test_returns_true_when_user_duplicate_constraint_is_violated(): void
    {
        $exception = $this->queryException('UNIQUE constraint failed: users.email');

        $this->assertTrue($this->detector->isDuplicate($exception));
    }

    public function test_returns_false_when_duplicate_constraint_is_not_user_constraint(): void
    {
        $exception = $this->queryException('UNIQUE constraint failed: posts.slug');

        $this->assertFalse($this->detector->isDuplicate($exception));
    }

    public function test_returns_false_when_error_is_not_duplicate_constraint(): void
    {
        $exception = $this->queryException('FOREIGN KEY constraint failed');

        $this->assertFalse($this->detector->isDuplicate($exception));
    }

    private function queryException(
        string $message,
    ): QueryException {
        return new QueryException(
            'sqlite',
            'insert into users',
            [],
            new Exception($message)
        );
    }
}
