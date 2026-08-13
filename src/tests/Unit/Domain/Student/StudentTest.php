<?php

namespace Tests\Unit\Domain\Student;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\Exceptions\StudentIdNotAssignedException;
use App\Domain\Student\StudentId;
use App\Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class StudentTest extends TestCase
{
    public function test_create_returns_unassigned_student(): void
    {
        $student = Student::create(
            new UserId(1),
        );

        self::assertNull($student->id());
        self::assertSame(1, $student->userId()->value());
    }

    public function test_reconstruct_restores_student_state(): void
    {
        $student = $this->student();

        self::assertSame(1, $student->id()->value());
        self::assertSame(10, $student->userId()->value());
    }

    public function test_require_id_returns_id_when_student_has_id(): void
    {
        $student = $this->student();

        self::assertSame(1, $student->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_null(): void
    {
        $this->expectException(StudentIdNotAssignedException::class);

        Student::create(new UserId(1))->requireId();
    }

    private function student(
        int $id = 1,
        int $userId = 10,
    ): Student {
        return Student::reconstruct(
            id: new StudentId($id),
            userId: new UserId($userId),
        );
    }
}
