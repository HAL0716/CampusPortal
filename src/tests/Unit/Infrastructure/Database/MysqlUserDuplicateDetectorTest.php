<?php

namespace Tests\Unit\Infrastructure\Database;

use App\Infrastructure\Database\MysqlUserDuplicateDetector;
use Illuminate\Database\QueryException;
use PDOException;
use Tests\TestCase;

final class MysqlUserDuplicateDetectorTest extends TestCase
{
    private MysqlUserDuplicateDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->detector = new MysqlUserDuplicateDetector(['users_email_unique']);
    }

    public function test_returns_true_when_user_duplicate_constraint_is_violated(): void
    {
        $exception = $this->queryException(
            1062,
            "Duplicate entry 'test@example.com' for key 'users_email_unique'"
        );

        $this->assertTrue($this->detector->isDuplicate($exception));
    }

    public function test_returns_false_when_duplicate_constraint_is_not_user_constraint(): void
    {
        $exception = $this->queryException(
            1062,
            "Duplicate entry 'test' for key 'posts_slug_unique'"
        );

        $this->assertFalse($this->detector->isDuplicate($exception));
    }

    public function test_returns_false_when_error_is_not_duplicate_entry(): void
    {
        $exception = $this->queryException(
            1452,
            'Cannot add or update a child row'
        );

        $this->assertFalse($this->detector->isDuplicate($exception));
    }

    private function queryException(
        int $code,
        string $message,
    ): QueryException {
        $pdoException = new PDOException($message);

        $pdoException->errorInfo = [
            '23000',
            $code,
            $message,
        ];

        return new QueryException(
            'mysql',
            'insert into users',
            [],
            $pdoException
        );
    }
}
