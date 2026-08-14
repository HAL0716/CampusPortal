<?php

namespace Tests\Unit\Application\Enrollment;

use App\Application\Enrollment\EnrollCommand;
use App\Application\Enrollment\EnrollUseCase;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Enrollment\EnrollmentTestHelper;
use Tests\Support\Matchers\UseMatcher;
use Tests\Support\Student\StudentTestHelper;
use Tests\TestCase;

class EnrollUseCaseTest extends TestCase
{
    use EnrollmentTestHelper;
    use MockeryPHPUnitIntegration;
    use StudentTestHelper;
    use UseMatcher;

    private StudentRepository&MockInterface $students;

    private EnrollmentRepositoryInterface&MockInterface $enrollments;

    private EnrollUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->students = Mockery::mock(StudentRepository::class);
        $this->enrollments = Mockery::mock(EnrollmentRepositoryInterface::class);

        $this->useCase = new EnrollUseCase(
            $this->students,
            $this->enrollments,
        );
    }

    public function test_creates_and_saves_enrollment_when_student_exists(): void
    {
        $student = $this->student();
        $command = $this->command();

        $this->expectStudent($this->students, $student);
        $this->expectEnrollment($this->enrollments, null, $student->requireId(), $command->courseOfferingId);

        $this->enrollments->shouldReceive('save')
            ->once()
            ->withArgs(fn (Enrollment $enrollment) => $enrollment->status() === EnrollmentStatus::ENROLLED
                && $enrollment->studentId()->value() === $student->requireId()->value()
                && $enrollment->courseOfferingId()->value() === $command->courseOfferingId->value())
            ->andReturnUsing(fn (Enrollment $enrollment) => $enrollment);

        $result = $this->useCase->execute($command);

        self::assertSame($student->requireId()->value(), $result->studentId()->value());
        self::assertSame(EnrollmentStatus::ENROLLED, $result->status());
    }

    public function test_re_enrolls_when_existing_enrollment_exists(): void
    {
        $student = $this->student();
        $command = $this->command();

        $existing = $this->enrollment(
            studentId: $student->requireId()->value(),
            courseOfferingId: $command->courseOfferingId->value(),
            status: EnrollmentStatus::DROPPED,
        );

        $this->expectStudent($this->students, $student);
        $this->expectEnrollment($this->enrollments, $existing, $student->requireId(), $command->courseOfferingId);

        $this->enrollments->shouldReceive('save')
            ->once()
            ->withArgs(fn (Enrollment $enrollment) => $enrollment->requireId()->value() === $existing->requireId()->value()
                && $enrollment->status() === EnrollmentStatus::ENROLLED)
            ->andReturnUsing(fn (Enrollment $enrollment) => $enrollment);

        $result = $this->useCase->execute($command);

        self::assertSame($existing->requireId()->value(), $result->requireId()->value());
        self::assertSame(EnrollmentStatus::ENROLLED, $result->status());
    }

    public function test_throws_exception_when_student_does_not_exist(): void
    {
        $this->expectStudent($this->students, null);
        $this->enrollments->shouldNotReceive('find');
        $this->enrollments->shouldNotReceive('save');

        $this->expectException(StudentNotFoundException::class);
        $this->useCase->execute($this->command());
    }

    private function command(): EnrollCommand
    {
        return new EnrollCommand(
            userId: $this->userId(),
            courseOfferingId: $this->courseOfferingId(),
        );
    }
}
