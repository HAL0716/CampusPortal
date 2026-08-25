<?php

namespace Tests\Unit\Domain\Semester;

use App\Domain\Semester\ValueObjects\SemesterId;
use Tests\TestCase;

final class SemesterIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new SemesterId(1);

        self::assertSame(1, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new SemesterId(1);

        self::assertTrue($id->equals(new SemesterId(1)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new SemesterId(1);

        self::assertFalse($id->equals(new SemesterId(2)));
    }
}
