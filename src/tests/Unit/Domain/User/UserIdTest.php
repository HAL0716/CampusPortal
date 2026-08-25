<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class UserIdTest extends TestCase
{
    public function test_creates_valid_user_id(): void
    {
        $id = new UserId(1);

        $this->assertSame(1, $id->value());
    }
}
