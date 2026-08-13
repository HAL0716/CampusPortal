<?php

namespace Tests\Unit\Domain\Teacher;

use App\Domain\Teacher\Exceptions\TeacherIdNotAssignedException;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\User\UserId;
use PHPUnit\Framework\TestCase;

final class TeacherTest extends TestCase
{
    public function test_create_returns_unassigned_teacher(): void
    {
        $teacher = Teacher::create(
            new UserId(1),
        );

        self::assertNull($teacher->id());
        self::assertSame(1, $teacher->userId()->value());
    }

    public function test_reconstruct_restores_teacher_state(): void
    {
        $teacher = $this->teacher();

        self::assertSame(1, $teacher->id()->value());
        self::assertSame(10, $teacher->userId()->value());
    }

    public function test_require_id_returns_id_when_teacher_has_id(): void
    {
        $teacher = $this->teacher();

        self::assertSame(1, $teacher->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_null(): void
    {
        $this->expectException(TeacherIdNotAssignedException::class);

        Teacher::create(new UserId(1))->requireId();
    }

    private function teacher(
        int $id = 1,
        int $userId = 10,
    ): Teacher {
        return Teacher::reconstruct(
            id: new TeacherId($id),
            userId: new UserId($userId),
        );
    }
}
