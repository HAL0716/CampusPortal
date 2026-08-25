<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Teacher\Entities\Teacher;

trait TeacherTestHelper
{
    use IdTestHelper;

    protected function createTeacher(
        ?int $userId = null,
    ): Teacher {
        return Teacher::create(
            userId: $this->userId($userId),
        );
    }

    protected function reconstructTeacher(
        ?int $id = null,
        ?int $userId = null,
    ): Teacher {
        return Teacher::reconstruct(
            id: $this->teacherId($id),
            userId: $this->userId($userId),
        );
    }
}
