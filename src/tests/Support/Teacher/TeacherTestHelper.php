<?php

namespace Tests\Support\Teacher;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepositoryInterface;
use App\Domain\User\UserId;
use Closure;
use Mockery\MockInterface;

trait TeacherTestHelper
{
    private function teacher(
        int $id = 20,
        int $userId = 100,
    ): Teacher {
        return Teacher::reconstruct(
            id: new TeacherId($id),
            userId: new UserId($userId),
        );
    }

    private function userId(?int $id = null): UserId
    {
        return new UserId($id ?? 100);
    }

    private function expectTeacher(
        TeacherRepositoryInterface&MockInterface $teachers,
        ?Teacher $teacher,
    ): void {
        $teachers
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs($this->userIdMatcher($teacher?->userId() ?? $this->userId()))
            ->andReturn($teacher);
    }

    private function userIdMatcher(UserId $expected): Closure
    {
        return fn (UserId $id) => $id->value() === $expected->value();
    }

    private function teacherIdMatcher(TeacherId $expected): Closure
    {
        return fn (TeacherId $id) => $id->value() === $expected->value();
    }
}
