<?php

namespace Tests\Unit\Application\Student;

use App\Application\Contexts\Student\Commands\UpdateStudentStatusCommand;
use App\Application\Contexts\Student\UseCases\UpdateStudentStatusUseCase;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\Exceptions\InvalidStatusTransition;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\StudentId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\StudentTestHelper;

final class UpdateStudentStatusUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use StudentTestHelper;

    private StudentRepository&MockInterface $students;

    private UpdateStudentStatusUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->students = Mockery::mock(StudentRepository::class);

        $this->useCase = new UpdateStudentStatusUseCase(
            $this->students,
        );
    }

    public function test_can_update_student_status(): void
    {
        $student = $this->reconstructStudent(
            status: StudentStatus::ACTIVE,
        );

        $this->students->shouldReceive('get')
            ->once()
            ->with($student->requireId())
            ->andReturn($student);

        $this->students->shouldReceive('save')
            ->once()
            ->with(Mockery::on(
                fn (Student $student) => $student->status() === StudentStatus::GRADUATED
            ))
            ->andReturn($this->reconstructStudent(
                id: $student->requireId()->value(),
                status: StudentStatus::GRADUATED,
            ));

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::GRADUATED,
            ),
        );
    }

    public function test_cannot_update_student_status_if_student_not_found(): void
    {
        $studentId = $this->studentId();

        $this->students->shouldReceive('get')
            ->once()
            ->with($studentId)
            ->andThrow(new StudentNotFoundException);

        $this->students->shouldNotReceive('save');

        $this->expectException(StudentNotFoundException::class);

        $this->useCase->execute(
            $this->command(
                studentId: $studentId,
                status: StudentStatus::GRADUATED,
            ),
        );
    }

    public function test_cannot_update_student_status_to_invalid_status(): void
    {
        $student = $this->reconstructStudent(
            status: StudentStatus::GRADUATED,
        );

        $this->students->shouldReceive('get')
            ->once()
            ->with($student->requireId())
            ->andReturn($student);

        $this->students->shouldNotReceive('save');

        $this->expectException(InvalidStatusTransition::class);

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::ACTIVE,
            ),
        );
    }

    private function command(
        StudentId $studentId,
        StudentStatus $status,
    ): UpdateStudentStatusCommand {
        return new UpdateStudentStatusCommand(
            studentId: $studentId,
            status: $status,
        );
    }
}
