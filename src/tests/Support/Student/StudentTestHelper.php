<?php

namespace Tests\Support\Student;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\UserId;
use Closure;
use Mockery\MockInterface;

trait StudentTestHelper
{
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
            ->withArgs($this->userIdMatcher($student?->userId() ?? $this->userId()))
            ->andReturn($student);
    }

    private function userIdMatcher(UserId $expected): Closure
    {
        return fn (UserId $id) => $id->value() === $expected->value();
    }

    private function studentIdMatcher(StudentId $expected): Closure
    {
        return fn (StudentId $id) => $id->value() === $expected->value();
    }
}
