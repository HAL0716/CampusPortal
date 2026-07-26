<?php

namespace Tests\Support\Student;

use App\Domain\Student\Student;
use App\Domain\Student\StudentRepositoryInterface;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;

trait StudentTestHelper
{
    use IdTestHelper;
    use UseMatcher;

    private function student(
        ?int $id = null,
        ?int $userId = null,
    ): Student {
        return Student::reconstruct(
            id: $this->studentId($id),
            userId: $this->userId($userId),
        );
    }

    private function expectStudent(
        StudentRepositoryInterface&MockInterface $students,
        ?Student $student,
    ): void {
        $students
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs($this->idMatcher($student?->userId() ?? $this->userId()))
            ->andReturn($student);
    }
}
