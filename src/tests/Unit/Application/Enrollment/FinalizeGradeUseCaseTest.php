<?php

namespace Tests\Unit\Application\Contexts\Enrollment;

use App\Application\Contexts\Enrollment\Commands\FinalizeGradeCommand;
use App\Application\Contexts\Enrollment\UseCases\FinalizeGradeUseCase;
use App\Application\Services\Authorization\EnrollmentAuthorizationService;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\FinalGrade\Entities\FinalGrade;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\FinalGrade\Repositories\FinalGradeRepository;
use App\Infrastructure\Authorization\Exceptions\UnauthorizedException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Enrollment\EnrollmentTestHelper;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;
use Tests\TestCase;

class FinalizeGradeUseCaseTest extends TestCase
{
    use EnrollmentTestHelper;
    use IdTestHelper;
    use MockeryPHPUnitIntegration;
    use UseMatcher;

    private EnrollmentRepository&MockInterface $enrollments;

    private FinalGradeRepository&MockInterface $finalGrades;

    private EnrollmentAuthorizationService&MockInterface $auth;

    private FinalizeGradeUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enrollments = Mockery::mock(EnrollmentRepository::class);
        $this->finalGrades = Mockery::mock(FinalGradeRepository::class);
        $this->auth = Mockery::mock(EnrollmentAuthorizationService::class);

        $this->useCase = new FinalizeGradeUseCase(
            $this->enrollments,
            $this->finalGrades,
            $this->auth,
        );
    }

    public function test_completes_and_saves_enrollment_and_final_grade_when_authorized(): void
    {
        $enrollment = $this->enrollment();
        $command = $this->command();

        $this->expectEnrollmentById($this->enrollments, $enrollment);

        $this->auth->shouldReceive('canManage')
            ->once()
            ->withArgs($this->idsMatcher($command->userId, $command->enrollmentId))
            ->andReturnTrue();

        $this->enrollments->shouldReceive('save')
            ->once()
            ->withArgs(fn (Enrollment $enrollment) => $enrollment->status() === EnrollmentStatus::COMPLETED)
            ->andReturnUsing(fn (Enrollment $enrollment) => $enrollment);

        $this->finalGrades->shouldReceive('save')
            ->once()
            ->withArgs(
                fn (FinalGrade $finalGrade) => $finalGrade->enrollmentId() === $command->enrollmentId
                    && $finalGrade->grade() === $command->grade
            )
            ->andReturnUsing(fn (FinalGrade $finalGrade) => $finalGrade);

        $this->useCase->execute($command);
    }

    public function test_throws_exception_when_enrollment_does_not_exist(): void
    {
        $command = $this->command();

        $this->expectEnrollmentById($this->enrollments, null);
        $this->auth->shouldNotReceive('canManage');
        $this->enrollments->shouldNotReceive('save');
        $this->finalGrades->shouldNotReceive('save');

        $this->expectException(EnrollmentNotFoundException::class);
        $this->useCase->execute($command);
    }

    public function test_throws_exception_when_user_is_not_authorized(): void
    {
        $enrollment = $this->enrollment();
        $command = $this->command();

        $this->expectEnrollmentById($this->enrollments, $enrollment);

        $this->auth->shouldReceive('canManage')
            ->once()
            ->withArgs($this->idsMatcher($this->userId(), $enrollment->requireId()))
            ->andReturnFalse();

        $this->enrollments->shouldNotReceive('save');
        $this->finalGrades->shouldNotReceive('save');

        $this->expectException(UnauthorizedException::class);
        $this->useCase->execute($command);
    }

    private function command(): FinalizeGradeCommand
    {
        return new FinalizeGradeCommand(
            userId: $this->userId(),
            enrollmentId: $this->enrollment()->requireId(),
            grade: FinalGradeType::A,
        );
    }
}
