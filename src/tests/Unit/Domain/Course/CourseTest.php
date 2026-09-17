<?php

namespace Tests\Unit\Domain\Course;

use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\CourseTestHelper;

final class CourseTest extends TestCase
{
    use CourseTestHelper;

    public function test_reconstructs_course_with_id(): void
    {
        $course = $this->reconstructCourse();

        $this->assertSame($this->courseId()->value(), $course->id()->value());
    }

    public function test_returns_assigned_id(): void
    {
        $course = $this->reconstructCourse();

        $this->assertSame($course->id()->value(), $course->requireId()->value());
    }
}
