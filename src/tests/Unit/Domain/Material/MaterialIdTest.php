<?php

namespace Tests\Unit\Domain\Material;

use App\Domain\Material\ValueObjects\MaterialId;
use Tests\TestCase;

final class MaterialIdTest extends TestCase
{
    public function test_can_get_value(): void
    {
        $id = new MaterialId(10);

        self::assertSame(10, $id->value());
    }

    public function test_equals_returns_true_when_values_are_same(): void
    {
        $id = new MaterialId(10);

        self::assertTrue($id->equals(new MaterialId(10)));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $id = new MaterialId(10);

        self::assertFalse($id->equals(new MaterialId(20)));
    }
}
