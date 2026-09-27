<?php

namespace Tests\Support\TestHelpers;

use App\Domain\CourseOffering\Entities\CourseOffering;

trait CourseOfferingTestHelper
{
    use IdTestHelper;

    protected function createCourseOffering(
        ?int $semesterId = null,
        ?int $courseId = null,
    ): CourseOffering {
        return CourseOffering::create(
            courseId: $this->courseId($courseId),
            semesterId: $this->semesterId($semesterId),
        );
    }

    protected function reconstructCourseOffering(
        ?int $id = null,
        ?int $semesterId = null,
        ?int $courseId = null,
    ): CourseOffering {
        return CourseOffering::reconstruct(
            id: $this->courseOfferingId($id),
            semesterId: $this->semesterId($semesterId),
            courseId: $this->courseId($courseId),
        );
    }
}
