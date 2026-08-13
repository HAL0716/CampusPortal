<?php

namespace Tests\Support\Teacher;

use App\Domain\Teacher\Entities\Teacher;
use App\Domain\Teacher\Repositories\TeacherRepository;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;

trait TeacherTestHelper
{
    use IdTestHelper;
    use UseMatcher;

    private function teacher(
        ?int $id = null,
        ?int $userId = null,
    ): Teacher {
        return Teacher::reconstruct(
            id: $this->teacherId($id),
            userId: $this->userId($userId),
        );
    }

    private function expectTeacher(
        TeacherRepository&MockInterface $teachers,
        ?Teacher $teacher,
    ): void {
        $teachers
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs($this->idMatcher($teacher?->userId() ?? $this->userId()))
            ->andReturn($teacher);
    }
}
