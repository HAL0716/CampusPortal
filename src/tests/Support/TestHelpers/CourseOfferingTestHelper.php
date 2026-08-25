<?php

namespace Tests\Support\TestHelpers;

use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\Teacher\ValueObjects\TeacherId;

trait CourseOfferingTestHelper
{
    use IdTestHelper;

    /**
     * @param  array<TeacherId>  $teacherIds
     */
    protected function reconstructCourseOffering(
        ?int $id = null,
        ?int $semesterId = null,
        ?int $courseId = null,
        array $teacherIds = [],
    ): CourseOffering {
        return CourseOffering::reconstruct(
            id: $this->courseOfferingId($id),
            semesterId: $this->semesterId($semesterId),
            courseId: $this->courseId($courseId),
            teacherIds: $teacherIds,
        );
    }
}
