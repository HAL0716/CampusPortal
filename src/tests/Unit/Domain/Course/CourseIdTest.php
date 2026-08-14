<?php

namespace Tests\Unit\Domain\Course;

use App\Domain\Course\ValueObjects\CourseId;
use Tests\TestCase;

final class CourseIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new CourseId(10);

        self::assertSame(10, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new CourseId(10);

        self::assertTrue($id->equals(new CourseId(10)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new CourseId(10);

        self::assertFalse($id->equals(new CourseId(20)));
    }
}
