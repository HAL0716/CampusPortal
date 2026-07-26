<?php

namespace Tests\Unit\Application\Enrollment;

use App\Application\Authorization\EnrollmentAuthorizationServiceInterface;
use App\Application\Enrollment\CompleteCommand;
use App\Application\Enrollment\CompleteUseCase;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Infrastructure\Authorization\Exceptions\UnauthorizedException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Enrollment\EnrollmentTestHelper;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;
use Tests\TestCase;

class CompleteUseCaseTest extends TestCase
{
    use EnrollmentTestHelper;
    use IdTestHelper;
    use MockeryPHPUnitIntegration;
    use UseMatcher;

    private EnrollmentRepositoryInterface&MockInterface $enrollments;

    private EnrollmentAuthorizationServiceInterface&MockInterface $auth;

    private CompleteUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enrollments = Mockery::mock(EnrollmentRepositoryInterface::class);
        $this->auth = Mockery::mock(EnrollmentAuthorizationServiceInterface::class);

        $this->useCase = new CompleteUseCase(
            $this->enrollments,
            $this->auth,
        );
    }

    public function test_completes_and_saves_enrollment_when_authorized(): void
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

        $this->useCase->execute($command);
    }

    public function test_throws_exception_when_enrollment_does_not_exist(): void
    {
        $command = $this->command();

        $this->expectEnrollmentById($this->enrollments, null);
        $this->auth->shouldNotReceive('canManage');
        $this->enrollments->shouldNotReceive('save');

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

        $this->expectException(UnauthorizedException::class);
        $this->useCase->execute($command);
    }

    private function command(): CompleteCommand
    {
        return new CompleteCommand(
            userId: $this->userId(),
            enrollmentId: $this->enrollment()->requireId(),
        );
    }
}
