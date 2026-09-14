<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\Enums\StudentStatus;

trait StudentTestHelper
{
    use IdTestHelper;

    protected function createStudent(
        ?int $userId = null,
        ?int $departmentId = null,
        ?string $studentNumber = null
    ): Student {
        return Student::create(
            userId: $this->userId($userId),
            departmentId: $this->departmentId($departmentId),
            studentNumber: $this->studentNumber($studentNumber),
        );
    }

    protected function reconstructStudent(
        ?int $id = null,
        ?int $userId = null,
        ?int $departmentId = null,
        ?string $studentNumber = null,
        ?StudentStatus $status = null
    ): Student {
        return Student::reconstruct(
            id: $this->studentId($id),
            userId: $this->userId($userId),
            departmentId: $this->departmentId($departmentId),
            studentNumber: $this->studentNumber($studentNumber),
            status: $status ?? StudentStatus::ACTIVE,
        );
    }
}
