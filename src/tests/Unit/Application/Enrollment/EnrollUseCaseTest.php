<?php

namespace Tests\Unit\Application\Contexts\Enrollment;

use App\Application\Contexts\Enrollment\Commands\EnrollCommand;
use App\Application\Contexts\Enrollment\UseCases\EnrollUseCase;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Repositories\StudentRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\TestCase;

final class EnrollUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;

    private StudentRepository&MockInterface $students;

    private EnrollmentRepository&MockInterface $enrollments;

    private EnrollUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->students = Mockery::mock(StudentRepository::class);
        $this->enrollments = Mockery::mock(EnrollmentRepository::class);

        $this->useCase = new EnrollUseCase(
            students: $this->students,
            enrollments: $this->enrollments,
        );
    }

    public function test_creates_enrollment_when_not_exists(): void
    {
        $userId = $this->userId();
        $studentId = $this->studentId();
        $courseOfferingId = $this->courseOfferingId();

        $student = Student::reconstruct(
            id: $studentId,
            userId: $userId,
        );

        $this->students
            ->shouldReceive('getByUserId')
            ->once()
            ->with($userId)
            ->andReturn($student);

        $this->enrollments
            ->shouldReceive('findByStudentAndCourseOffering')
            ->once()
            ->with($studentId, $courseOfferingId)
            ->andReturnNull();

        $this->enrollments
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(Enrollment::class))
            ->andReturnUsing(
                fn (Enrollment $enrollment): Enrollment => $enrollment,
            );

        $result = $this->useCase->execute(
            new EnrollCommand(
                userId: $userId,
                courseOfferingId: $courseOfferingId,
            ),
        );

        self::assertInstanceOf(Enrollment::class, $result);
    }

    public function test_re_enrolls_existing_enrollment(): void
    {
        $userId = $this->userId();
        $studentId = $this->studentId();
        $courseOfferingId = $this->courseOfferingId();

        $student = Student::reconstruct(
            id: $studentId,
            userId: $userId,
        );

        $enrollment = Enrollment::create(
            studentId: $studentId,
            courseOfferingId: $courseOfferingId,
        );

        $this->students
            ->shouldReceive('getByUserId')
            ->once()
            ->with($userId)
            ->andReturn($student);

        $this->enrollments
            ->shouldReceive('findByStudentAndCourseOffering')
            ->once()
            ->with($studentId, $courseOfferingId)
            ->andReturn($enrollment);

        $this->enrollments
            ->shouldReceive('save')
            ->once()
            ->with($enrollment)
            ->andReturn($enrollment);

        $result = $this->useCase->execute(
            new EnrollCommand(
                userId: $userId,
                courseOfferingId: $courseOfferingId,
            ),
        );

        self::assertSame($enrollment, $result);
    }
}
