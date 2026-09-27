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
}
