<?php

namespace Tests\Unit\Application\Enrollment;

use App\Application\Enrollment\EnrollCommand;
use App\Application\Enrollment\EnrollUseCase;
use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\UserId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Matcher\Closure;
use Mockery\MockInterface;
use Tests\TestCase;

class EnrollUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const USER_ID = 10;

    private StudentRepositoryInterface&MockInterface $students;

    private EnrollmentRepositoryInterface&MockInterface $enrollments;

    private EnrollUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->students = Mockery::mock(StudentRepositoryInterface::class);
        $this->enrollments = Mockery::mock(EnrollmentRepositoryInterface::class);

        $this->useCase = new EnrollUseCase($this->students, $this->enrollments);
    }

    public function test_can_enroll_when_student_exists(): void
    {
        $student = Student::reconstruct(
            id: new StudentId(1),
            userId: new UserId(self::USER_ID)
        );

        $this->students
            ->shouldReceive('findByUserId')
            ->once()
            ->with($this->userId(self::USER_ID))
            ->andReturn($student);

        $this->enrollments
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(Enrollment::class))
            ->andReturnUsing(fn (Enrollment $enrollment) => $enrollment);

        $result = $this->useCase->execute($this->command());

        $this->assertInstanceOf(Enrollment::class, $result);
    }

    public function test_can_not_enroll_when_student_does_not_exist(): void
    {
        $this->students
            ->shouldReceive('findByUserId')
            ->once()
            ->with($this->userId(999))
            ->andReturn(null);

        $this->expectException(StudentNotFoundException::class);

        $this->useCase->execute($this->command(userId: 999));
    }

    private function command(int $userId = self::USER_ID): EnrollCommand
    {
        return new EnrollCommand(
            userId: new UserId($userId),
            courseOfferingId: new CourseOfferingId(100)
        );
    }

    private function userId(int $value): Closure
    {
        return Mockery::on(fn (UserId $id) => $id->value() === $value);
    }
}
