<?php

namespace Tests\Unit\Domain\Teacher;

use App\Domain\Teacher\TeacherId;
use Tests\TestCase;

final class TeacherIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new TeacherId(10);

        self::assertSame(10, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new TeacherId(10);

        self::assertTrue($id->equals(new TeacherId(10)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new TeacherId(10);

        self::assertFalse($id->equals(new TeacherId(20)));
    }
}
