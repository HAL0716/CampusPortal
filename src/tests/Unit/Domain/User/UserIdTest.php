<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class UserIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new UserId(1);

        self::assertSame(1, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new UserId(1);

        self::assertTrue($id->equals(new UserId(1)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new UserId(1);

        self::assertFalse($id->equals(new UserId(2)));
    }
}
