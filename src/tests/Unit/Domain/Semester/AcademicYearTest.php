<?php

namespace Tests\Unit\Domain\Semester;

use App\Domain\Semester\Exceptions\InvalidAcademicYear;
use App\Domain\Semester\ValueObjects\AcademicYear;
use PHPUnit\Framework\TestCase;

final class AcademicYearTest extends TestCase
{
    public function test_creates_academic_year(): void
    {
        $academicYear = new AcademicYear('2026');

        $this->assertSame('2026', $academicYear->value());
    }

    public function test_trims_whitespace(): void
    {
        $academicYear = new AcademicYear(' 2026 ');

        $this->assertSame('2026', $academicYear->value());
    }

    public function test_throws_exception_for_invalid_value(): void
    {
        $this->expectException(InvalidAcademicYear::class);

        new AcademicYear('202');
    }

    public function test_next_returns_next_academic_year(): void
    {
        $academicYear = new AcademicYear('2026');

        $next = $academicYear->next();

        $this->assertSame('2027', $next->value());
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $academicYear = new AcademicYear('2026');

        $this->assertTrue($academicYear->equals(new AcademicYear('2026')));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $academicYear = new AcademicYear('2026');

        $this->assertFalse($academicYear->equals(new AcademicYear('2027')));
    }
}
