<?php

namespace Tests\Unit\Infrastructure\Database\Mysql;

use App\Application\Database\DuplicateDetectorInterface;
use App\Application\User\UserDuplicateTarget;
use App\Infrastructure\Database\Mysql\MysqlUserDuplicateDetector;

final class MysqlUserDuplicateDetectorTest extends MysqlDuplicateDetectorTestCase
{
    protected function detector(): DuplicateDetectorInterface
    {
        return new MysqlUserDuplicateDetector;
    }

    public function test_returns_true_when_user_email_is_duplicate(): void
    {
        $this->assertDuplicate(
            UserDuplicateTarget::EMAIL,
            "Duplicate entry 'test@example.com' for key 'users_email_unique'",
            true,
        );
    }

    public function test_returns_false_when_non_user_constraint_is_violated(): void
    {
        $this->assertDuplicate(
            UserDuplicateTarget::EMAIL,
            "Duplicate entry 'test' for key 'posts_slug_unique'",
            false,
        );
    }

    public function test_returns_false_when_error_is_not_duplicate(): void
    {
        $this->assertDuplicate(
            UserDuplicateTarget::EMAIL,
            'Cannot add or update a child row',
            false,
            1452,
        );
    }
}
