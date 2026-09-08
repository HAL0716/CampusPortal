<?php

namespace Tests\Unit\Domain\Permission;

use App\Domain\Permission\ValueObjects\PermissionId;
use PHPUnit\Framework\TestCase;

final class PermissionIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new PermissionId(1);

        self::assertSame(1, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new PermissionId(1);

        self::assertTrue($id->equals(new PermissionId(1)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new PermissionId(1);

        self::assertFalse($id->equals(new PermissionId(2)));
    }
}
