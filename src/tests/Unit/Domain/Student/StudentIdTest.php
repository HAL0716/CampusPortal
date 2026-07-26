<?php

namespace Tests\Unit\Domain\Student;

use App\Domain\Student\StudentId;
use Tests\TestCase;

final class StudentIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new StudentId(10);

        self::assertSame(10, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new StudentId(10);

        self::assertTrue($id->equals(new StudentId(10)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new StudentId(10);

        self::assertFalse($id->equals(new StudentId(20)));
    }
}
