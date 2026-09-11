<?php

namespace Tests\Unit\Application\Student;

use App\Application\Contexts\Student\Commands\UpdateStudentStatusCommand;
use App\Application\Contexts\Student\UseCases\UpdateStudentStatusUseCase;
use App\Application\Services\Database\Transaction;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\Exceptions\InsufficientCredits;
use App\Domain\Student\Exceptions\InvalidStatusTransition;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Policies\GraduationPolicy;
use App\Domain\Student\Policies\TransitionPolicy;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\User\Entities\User;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Repositories\UserRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\StudentTestHelper;
use Tests\Support\TestHelpers\UserTestHelper;

final class UpdateStudentStatusUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use StudentTestHelper;
    use UserTestHelper;

    private const REQUIRED_CREDITS = 124;

    private UserRepository&MockInterface $users;

    private StudentRepository&MockInterface $students;

    private Transaction&MockInterface $transaction;

    private UpdateStudentStatusUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepository::class);
        $this->students = Mockery::mock(StudentRepository::class);
        $this->transaction = Mockery::mock(Transaction::class);

        $transition = new TransitionPolicy(
            new GraduationPolicy(
                requiredCredits: self::REQUIRED_CREDITS,
            ),
        );

        $this->transaction->shouldReceive('run')
            ->andReturnUsing(
                fn (callable $callback) => $callback(),
            );

        $this->useCase = new UpdateStudentStatusUseCase(
            $this->users,
            $this->students,
            $transition,
            $this->transaction,
        );
    }

    public function test_can_graduate_student_and_deactivate_user(): void
    {
        $user = $this->reconstructUser();

        $student = $this->reconstructStudent(
            userId: $user->requireId()->value(),
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

        $this->users->shouldReceive('get')
            ->once()
            ->with($student->userId())
            ->andReturn($user);

        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::on(
                fn (User $user) => $user->status() === UserStatus::INACTIVE
            ))
            ->andReturn($user->deactivate());

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::GRADUATED,
                credits: self::REQUIRED_CREDITS,
            ),
        );
    }

    public function test_can_suspend_student_without_deactivating_user(): void
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
                fn (Student $student) => $student->status() === StudentStatus::SUSPENDED
            ))
            ->andReturn($this->reconstructStudent(
                id: $student->requireId()->value(),
                status: StudentStatus::SUSPENDED,
            ));

        $this->users->shouldNotReceive('get');
        $this->users->shouldNotReceive('save');

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::SUSPENDED,
                credits: 0,
            ),
        );
    }

    public function test_can_expel_student_and_deactivate_user(): void
    {
        $user = $this->reconstructUser();

        $student = $this->reconstructStudent(
            userId: $user->requireId()->value(),
            status: StudentStatus::ACTIVE,
        );

        $this->students->shouldReceive('get')
            ->once()
            ->with($student->requireId())
            ->andReturn($student);

        $this->students->shouldReceive('save')
            ->once()
            ->with(Mockery::on(
                fn (Student $student) => $student->status() === StudentStatus::EXPELLED
            ))
            ->andReturn($this->reconstructStudent(
                id: $student->requireId()->value(),
                status: StudentStatus::EXPELLED,
            ));

        $this->users->shouldReceive('get')
            ->once()
            ->with($student->userId())
            ->andReturn($user);

        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::on(
                fn (User $user) => $user->status() === UserStatus::INACTIVE
            ))
            ->andReturn($user->deactivate());

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::EXPELLED,
                credits: 0,
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
        $this->users->shouldNotReceive('get');
        $this->users->shouldNotReceive('save');

        $this->expectException(StudentNotFoundException::class);

        $this->useCase->execute(
            $this->command(
                studentId: $studentId,
                status: StudentStatus::GRADUATED,
                credits: self::REQUIRED_CREDITS,
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
        $this->users->shouldNotReceive('get');
        $this->users->shouldNotReceive('save');

        $this->expectException(InvalidStatusTransition::class);

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::ACTIVE,
                credits: self::REQUIRED_CREDITS,
            ),
        );
    }

    public function test_cannot_update_student_status_to_graduated_with_insufficient_credits(): void
    {
        $student = $this->reconstructStudent(
            status: StudentStatus::ACTIVE,
        );

        $this->students->shouldReceive('get')
            ->once()
            ->with($student->requireId())
            ->andReturn($student);

        $this->students->shouldNotReceive('save');
        $this->users->shouldNotReceive('get');
        $this->users->shouldNotReceive('save');

        $this->expectException(InsufficientCredits::class);

        $this->useCase->execute(
            $this->command(
                studentId: $student->requireId(),
                status: StudentStatus::GRADUATED,
                credits: self::REQUIRED_CREDITS - 1,
            ),
        );
    }

    private function command(
        StudentId $studentId,
        StudentStatus $status,
        int $credits,
    ): UpdateStudentStatusCommand {
        return new UpdateStudentStatusCommand(
            studentId: $studentId,
            status: $status,
            credits: $credits,
        );
    }
}
