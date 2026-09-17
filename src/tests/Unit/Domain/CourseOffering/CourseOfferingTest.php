<?php

namespace Tests\Unit\Domain\CourseOffering;

use App\Domain\CourseOffering\Exceptions\CourseOfferingIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\CourseOfferingTestHelper;

final class CourseOfferingTest extends TestCase
{
    use CourseOfferingTestHelper;

    public function test_create_course_offering_without_id(): void
    {
        $courseOffering = $this->createCourseOffering();

        $this->assertNull($courseOffering->id());
        $this->assertSame($this->courseId()->value(), $courseOffering->courseId()->value());
        $this->assertSame($this->semesterId()->value(), $courseOffering->semesterId()->value());
    }

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

    public function test_returns_assigned_id(): void
    {
        $courseOffering = $this->reconstructCourseOffering();

        $this->assertSame($this->courseOfferingId()->value(), $courseOffering->requireId()->value());
    }

    public function test_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(CourseOfferingIdNotAssignedException::class);

        $this->createCourseOffering()->requireId();
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
