<?php

namespace Tests\Support\Teacher;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepositoryInterface;
use App\Domain\User\UserId;
use Mockery\MockInterface;
use Tests\Support\Matchers\UseMatcher;

trait TeacherTestHelper
{
    use UseMatcher;

    private function teacher(
        ?int $id = null,
        ?int $userId = null,
    ): Teacher {
        return Teacher::reconstruct(
            id: new TeacherId($id ?? 20),
            userId: new UserId($userId ?? 100),
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
            ->withArgs($this->idMatcher($teacher?->userId() ?? $this->userId()))
            ->andReturn($teacher);
    }
}
