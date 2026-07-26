<?php

namespace Tests\Support\Student;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\UserId;
use Mockery\MockInterface;
use Tests\Support\Matchers\UseMatcher;

trait StudentTestHelper
{
    use UseMatcher;

    private function student(
        ?int $id = null,
        ?int $userId = null,
    ): Student {
        return Student::reconstruct(
            id: new StudentId($id ?? 1),
            userId: new UserId($userId ?? 100),
        );
    }

    private function userId(?int $id = null): UserId
    {
        return new UserId($id ?? 100);
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
