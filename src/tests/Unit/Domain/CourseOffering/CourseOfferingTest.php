<?php

namespace Tests\Unit\Domain\CourseOffering;

use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\CourseOfferingTestHelper;

final class CourseOfferingTest extends TestCase
{
    use CourseOfferingTestHelper;

    public function test_reconstruct_restores_course_offering(): void
    {
        $courseOffering = $this->reconstructCourseOffering();

        $this->assertSame($this->courseOfferingId()->value(), $courseOffering->id()->value());
        $this->assertSame($this->courseId()->value(), $courseOffering->courseId()->value());
        $this->assertSame($this->semesterId()->value(), $courseOffering->semesterId()->value());
        $this->assertSame([], $courseOffering->teacherIds());
    }

    public function test_reconstruct_restores_teacher_ids(): void
    {
        $teacherIds = [$this->teacherId(1), $this->teacherId(2)];

        $courseOffering = $this->reconstructCourseOffering(teacherIds: $teacherIds);

        $this->assertCount(2, $courseOffering->teacherIds());
        $this->assertSame(1, $courseOffering->teacherIds()[0]->value());
        $this->assertSame(2, $courseOffering->teacherIds()[1]->value());
    }

    public function test_has_teacher_returns_true_when_teacher_is_assigned(): void
    {
        $courseOffering = $this->reconstructCourseOffering(
            teacherIds: [$this->teacherId()],
        );

        $this->assertTrue($courseOffering->hasTeacher($this->teacherId()));
    }

    public function test_has_teacher_returns_false_when_teacher_is_not_assigned(): void
    {
        $courseOffering = $this->reconstructCourseOffering(
            teacherIds: [$this->teacherId()],
        );

        $this->assertFalse($courseOffering->hasTeacher($this->teacherId(2)));
    }

    public function test_has_teacher_returns_false_when_no_teacher_is_assigned(): void
    {
        $courseOffering = $this->reconstructCourseOffering();

        $this->assertFalse($courseOffering->hasTeacher($this->teacherId()));
    }
}
