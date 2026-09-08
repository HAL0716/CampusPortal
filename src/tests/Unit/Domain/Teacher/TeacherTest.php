<?php

namespace Tests\Unit\Domain\Teacher;

use App\Domain\Teacher\Exceptions\TeacherIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\TeacherTestHelper;

final class TeacherTest extends TestCase
{
    use TeacherTestHelper;

    public function test_creates_teacher_without_id(): void
    {
        $teacher = $this->createTeacher();

        $this->assertNull($teacher->id());
        $this->assertSame($this->userId()->value(), $teacher->userId()->value());
    }

    public function test_reconstructs_teacher_with_id(): void
    {
        $teacher = $this->reconstructTeacher();

        $this->assertSame($this->teacherId()->value(), $teacher->id()->value());
        $this->assertSame($this->userId()->value(), $teacher->userId()->value());
    }

    public function test_returns_assigned_id(): void
    {
        $teacher = $this->reconstructTeacher();

        $this->assertSame($this->teacherId()->value(), $teacher->requireId()->value());
    }

    public function test_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(TeacherIdNotAssignedException::class);

        $this->createTeacher()->requireId();
    }
}
