<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Course\Entities\Course;

trait CourseTestHelper
{
    use IdTestHelper;

    protected function reconstructCourse(
        ?int $id = null,
    ): Course {
        return Course::reconstruct(
            id: $this->courseId($id)
        );
    }
}
