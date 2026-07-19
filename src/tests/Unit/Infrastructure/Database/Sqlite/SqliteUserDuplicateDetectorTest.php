<?php

namespace Tests\Unit\Infrastructure\Database\Sqlite;

use App\Application\Database\DuplicateDetectorInterface;
use App\Application\User\UserDuplicateTarget;
use App\Infrastructure\Database\Sqlite\SqliteUserDuplicateDetector;

final class SqliteUserDuplicateDetectorTest extends SqliteDuplicateDetectorTestCase
{
    protected function detector(): DuplicateDetectorInterface
    {
        return new SqliteUserDuplicateDetector;
    }

    public function test_returns_true_when_user_email_is_duplicate(): void
    {
        $this->assertDuplicate(
            UserDuplicateTarget::EMAIL,
            'UNIQUE constraint failed: users.email',
            true,
        );
    }

    public function test_returns_false_when_non_user_constraint_is_violated(): void
    {
        $this->assertDuplicate(
            UserDuplicateTarget::EMAIL,
            'UNIQUE constraint failed: posts.slug',
            false,
        );
    }

    public function test_returns_false_when_error_is_not_duplicate(): void
    {
        $this->assertDuplicate(
            UserDuplicateTarget::EMAIL,
            'FOREIGN KEY constraint failed',
            false,
            5,
        );
    }
}
