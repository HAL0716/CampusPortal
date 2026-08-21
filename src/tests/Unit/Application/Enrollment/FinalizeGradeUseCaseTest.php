<?php

namespace Tests\Unit\Application\Contexts\Enrollment;

use App\Application\Contexts\Enrollment\Commands\FinalizeGradeCommand;
use App\Application\Contexts\Enrollment\UseCases\FinalizeGradeUseCase;
use App\Application\Exceptions\ForbiddenException;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\FinalGrade\Entities\FinalGrade;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\FinalGrade\Repositories\FinalGradeRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\TestCase;

final class FinalizeGradeUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;

    private EnrollmentRepository&MockInterface $enrollments;

    private FinalGradeRepository&MockInterface $finalGrades;

    private CourseOfferingAuthorizationService&MockInterface $auth;

    private FinalizeGradeUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enrollments = Mockery::mock(EnrollmentRepository::class);
        $this->finalGrades = Mockery::mock(FinalGradeRepository::class);
        $this->auth = Mockery::mock(CourseOfferingAuthorizationService::class);

        $this->useCase = new FinalizeGradeUseCase(
            enrollments: $this->enrollments,
            finalGrades: $this->finalGrades,
            auth: $this->auth,
        );
    }

    public function test_completes_enrollment_when_grade_is_not_f(): void
    {
        $userId = $this->userId();
        $enrollmentId = $this->enrollmentId();
        $studentId = $this->studentId();
        $courseOfferingId = $this->courseOfferingId();
        $grade = FinalGradeType::A;

        $enrollment = Enrollment::reconstruct(
            id: $enrollmentId,
            studentId: $studentId,
            courseOfferingId: $courseOfferingId,
            status: EnrollmentStatus::ENROLLED,
        );

        $this->enrollments
            ->shouldReceive('getById')
            ->once()
            ->with($enrollmentId)
            ->andReturn($enrollment);

        $this->auth
            ->shouldReceive('canManage')
            ->once()
            ->with($userId, $courseOfferingId)
            ->andReturnTrue();

        $this->finalGrades
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (FinalGrade $finalGrade) use ($enrollmentId, $grade): bool {
                return $finalGrade->enrollmentId() === $enrollmentId
                    && $finalGrade->grade() === $grade;
            })
            ->andReturnUsing(
                fn (FinalGrade $finalGrade): FinalGrade => $finalGrade,
            );

        $this->enrollments
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (Enrollment $saved) use ($enrollmentId): bool {
                return $saved->id() === $enrollmentId
                    && $saved->status() === EnrollmentStatus::COMPLETED;
            })
            ->andReturnUsing(
                fn (Enrollment $enrollment): Enrollment => $enrollment,
            );

        $this->useCase->execute(
            new FinalizeGradeCommand(
                userId: $userId,
                enrollmentId: $enrollmentId,
                grade: $grade,
            ),
        );
    }

    public function test_fails_enrollment_when_grade_is_f(): void
    {
        $userId = $this->userId();
        $enrollmentId = $this->enrollmentId();
        $studentId = $this->studentId();
        $courseOfferingId = $this->courseOfferingId();
        $grade = FinalGradeType::F;

        $enrollment = Enrollment::reconstruct(
            id: $enrollmentId,
            studentId: $studentId,
            courseOfferingId: $courseOfferingId,
            status: EnrollmentStatus::ENROLLED,
        );

        $this->enrollments
            ->shouldReceive('getById')
            ->once()
            ->with($enrollmentId)
            ->andReturn($enrollment);

        $this->auth
            ->shouldReceive('canManage')
            ->once()
            ->with($userId, $courseOfferingId)
            ->andReturnTrue();

        $this->finalGrades
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (FinalGrade $finalGrade) use ($enrollmentId, $grade): bool {
                return $finalGrade->enrollmentId() === $enrollmentId
                    && $finalGrade->grade() === $grade;
            })
            ->andReturnUsing(
                fn (FinalGrade $finalGrade): FinalGrade => $finalGrade,
            );

        $this->enrollments
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(Enrollment::class))
            ->andReturnUsing(
                fn (Enrollment $enrollment): Enrollment => $enrollment,
            );

        $this->useCase->execute(
            new FinalizeGradeCommand(
                userId: $userId,
                enrollmentId: $enrollmentId,
                grade: $grade,
            ),
        );
    }

    public function test_throws_forbidden_exception_when_user_cannot_manage(): void
    {
        $userId = $this->userId();
        $enrollmentId = $this->enrollmentId();
        $studentId = $this->studentId();
        $courseOfferingId = $this->courseOfferingId();

        $enrollment = Enrollment::reconstruct(
            id: $enrollmentId,
            studentId: $studentId,
            courseOfferingId: $courseOfferingId,
            status: EnrollmentStatus::ENROLLED,
        );

        $this->enrollments
            ->shouldReceive('getById')
            ->once()
            ->with($enrollmentId)
            ->andReturn($enrollment);

        $this->auth
            ->shouldReceive('canManage')
            ->once()
            ->with($userId, $courseOfferingId)
            ->andReturnFalse();

        $this->finalGrades
            ->shouldNotReceive('save');

        $this->enrollments
            ->shouldNotReceive('save');

        $this->expectException(ForbiddenException::class);

        $this->useCase->execute(
            new FinalizeGradeCommand(
                userId: $userId,
                enrollmentId: $enrollmentId,
                grade: FinalGradeType::A,
            ),
        );
    }
}
