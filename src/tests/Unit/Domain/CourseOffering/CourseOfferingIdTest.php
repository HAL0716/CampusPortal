<?php

namespace Tests\Unit\Domain\CourseOffering;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use Tests\TestCase;

final class CourseOfferingIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new CourseOfferingId(1);

        self::assertSame(1, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new CourseOfferingId(1);

        self::assertTrue($id->equals(new CourseOfferingId(1)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new CourseOfferingId(1);

        self::assertFalse($id->equals(new CourseOfferingId(2)));
    }
}
