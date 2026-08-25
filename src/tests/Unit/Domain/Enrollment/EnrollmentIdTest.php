<?php

namespace Tests\Unit\Domain\Enrollment;

use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use Tests\TestCase;

final class EnrollmentIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new EnrollmentId(1);

        self::assertSame(1, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new EnrollmentId(1);

        self::assertTrue($id->equals(new EnrollmentId(1)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new EnrollmentId(1);

        self::assertFalse($id->equals(new EnrollmentId(2)));
    }
}
