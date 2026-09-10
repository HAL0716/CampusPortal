<?php

namespace Tests\Unit\Domain\Student;

use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\Exceptions\InvalidStatusTransition;
use App\Domain\Student\Exceptions\StudentIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\StudentTestHelper;

final class StudentTest extends TestCase
{
    use StudentTestHelper;

    public function test_creates_student_without_id(): void
    {
        $student = $this->createStudent();

        $this->assertNull($student->id());
        $this->assertSame($this->userId()->value(), $student->userId()->value());
        $this->assertSame($this->departmentId()->value(), $student->departmentId()->value());
    }

    public function test_reconstructs_student_with_id(): void
    {
        $student = $this->reconstructStudent();

        $this->assertSame($this->studentId()->value(), $student->id()->value());
        $this->assertSame($this->userId()->value(), $student->userId()->value());
        $this->assertSame($this->departmentId()->value(), $student->departmentId()->value());
    }

    public function test_returns_assigned_id(): void
    {
        $student = $this->reconstructStudent();

        $this->assertSame($this->studentId()->value(), $student->requireId()->value());
    }

    public function test_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(StudentIdNotAssignedException::class);

        $this->createStudent()->requireId();
    }

    public function test_can_transition_student_status(): void
    {
        $before = $this->reconstructStudent(status: StudentStatus::ACTIVE);

        $after = $before->transitionTo(StudentStatus::GRADUATED);

        $this->assertSame(StudentStatus::ACTIVE, $before->status());
        $this->assertSame(StudentStatus::GRADUATED, $after->status());
    }

    public function test_throws_exception_for_invalid_student_status_transition(): void
    {
        $student = $this->reconstructStudent(status: StudentStatus::GRADUATED);

        $this->expectException(InvalidStatusTransition::class);

        $student->transitionTo(StudentStatus::ACTIVE);
    }
}
