<?php

namespace Tests\Unit\Domain\CourseOffering;

use App\Domain\Course\CourseId;
use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use Tests\TestCase;

final class CourseOfferingTest extends TestCase
{
    public function test_reconstruct_restores_course_offering_state(): void
    {
        $offering = $this->courseOffering();

        self::assertSame(1, $offering->id()->value());
        self::assertSame(2, $offering->semesterId()->value());
        self::assertSame(3, $offering->courseId()->value());
        self::assertSame([], $offering->teacherIds());
    }

    public function test_has_teacher_returns_true_when_teacher_is_assigned(): void
    {
        $teacherId = new TeacherId(10);

        $offering = $this->courseOffering(
            teacherIds: [$teacherId],
        );

        self::assertTrue($offering->hasTeacher($teacherId));
    }

    public function test_has_teacher_returns_false_when_teacher_is_not_assigned(): void
    {
        $offering = $this->courseOffering(
            teacherIds: [new TeacherId(10)],
        );

        self::assertFalse($offering->hasTeacher(new TeacherId(99)));
    }

    private function courseOffering(
        int $id = 1,
        int $semesterId = 2,
        int $courseId = 3,
        array $teacherIds = [],
    ): CourseOffering {
        return CourseOffering::reconstruct(
            id: new CourseOfferingId($id),
            semesterId: new SemesterId($semesterId),
            courseId: new CourseId($courseId),
            teacherIds: $teacherIds,
        );
    }
}
