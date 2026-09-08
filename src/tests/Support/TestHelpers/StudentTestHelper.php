<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Student\Entities\Student;

trait StudentTestHelper
{
    use IdTestHelper;

    protected function createStudent(?int $userId = null): Student
    {
        return Student::create(
            userId: $this->userId($userId),
        );
    }

    protected function reconstructStudent(
        ?int $id = null,
        ?int $userId = null,
    ): Student {
        return Student::reconstruct(
            id: $this->studentId($id),
            userId: $this->userId($userId),
        );
    }
}
