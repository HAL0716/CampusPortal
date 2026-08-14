<?php

namespace Tests\Unit\Application\Enrollment;

use App\Application\Enrollment\DropCommand;
use App\Application\Enrollment\DropUseCase;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Enrollment\EnrollmentTestHelper;
use Tests\Support\Matchers\UseMatcher;
use Tests\Support\Student\StudentTestHelper;
use Tests\TestCase;

class DropUseCaseTest extends TestCase
{
    use EnrollmentTestHelper;
    use MockeryPHPUnitIntegration;
    use StudentTestHelper;
    use UseMatcher;

    private StudentRepository&MockInterface $students;

    private EnrollmentRepositoryInterface&MockInterface $enrollments;

    private DropUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->students = Mockery::mock(StudentRepository::class);
        $this->enrollments = Mockery::mock(EnrollmentRepositoryInterface::class);

        $this->useCase = new DropUseCase(
            $this->students,
            $this->enrollments,
        );
    }

    public function test_drops_and_saves_enrollment_when_student_and_enrollment_exist(): void
    {
        $student = $this->student();
        $command = $this->command();

        $enrollment = $this->enrollment(
            studentId: $student->requireId()->value(),
            courseOfferingId: $command->courseOfferingId->value(),
            status: EnrollmentStatus::ENROLLED,
        );

        $this->expectStudent($this->students, $student);
        $this->expectEnrollment($this->enrollments, $enrollment, $student->requireId(), $command->courseOfferingId);

        $this->enrollments->shouldReceive('save')
            ->once()
            ->withArgs(fn (Enrollment $saved) => $saved->requireId()->value() === $enrollment->requireId()->value()
                && $saved->status() === EnrollmentStatus::DROPPED)
            ->andReturnUsing(fn (Enrollment $enrollment) => $enrollment);

        $result = $this->useCase->execute($command);

        self::assertSame($enrollment->requireId()->value(), $result->requireId()->value());
        self::assertSame(EnrollmentStatus::DROPPED, $result->status());
    }

    public function test_throws_exception_when_student_does_not_exist(): void
    {
        $this->expectStudent($this->students, null);
        $this->enrollments->shouldNotReceive('find');
        $this->enrollments->shouldNotReceive('save');

        $this->expectException(StudentNotFoundException::class);
        $this->useCase->execute($this->command());
    }

    public function test_throws_exception_when_enrollment_does_not_exist(): void
    {
        $student = $this->student();
        $command = $this->command();

        $this->expectStudent($this->students, $student);
        $this->expectEnrollment($this->enrollments, null, $student->requireId(), $command->courseOfferingId);

        $this->enrollments->shouldNotReceive('save');

        $this->expectException(EnrollmentNotFoundException::class);
        $this->useCase->execute($command);
    }

    private function command(): DropCommand
    {
        return new DropCommand(
            userId: $this->userId(),
            courseOfferingId: $this->courseOfferingId(),
        );
    }
}
