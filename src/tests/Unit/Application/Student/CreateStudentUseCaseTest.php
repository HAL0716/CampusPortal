<?php

namespace Tests\Unit\Application\Contexts\Student;

use App\Application\Contexts\Student\Commands\CreateStudentCommand;
use App\Application\Contexts\Student\UseCases\CreateStudentUseCase;
use App\Application\Services\Database\Transaction;
use App\Domain\Role\Enums\RoleType;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Repositories\UserRoleRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\StudentTestHelper;
use Tests\Support\TestHelpers\UserTestHelper;

final class CreateStudentUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use StudentTestHelper;
    use UserTestHelper;

    private UserRepository&MockInterface $users;

    private UserRoleRepository&MockInterface $userRoles;

    private StudentRepository&MockInterface $students;

    private Transaction&MockInterface $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepository::class);
        $this->userRoles = Mockery::mock(UserRoleRepository::class);
        $this->students = Mockery::mock(StudentRepository::class);
        $this->transaction = Mockery::mock(Transaction::class);

        $this->transaction->shouldReceive('run')
            ->once()
            ->andReturnUsing(
                fn (callable $callback) => $callback()
            );
    }

    public function test_creates_student(): void
    {
        $user = $this->reconstructUser();

        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturn($user);

        $this->students->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (Student $student) use ($user): bool {
                return $student->userId()->value() === $user->requireId()->value()
                    && $student->departmentId()->value() === $this->departmentId()->value();
            }))
            ->andReturn($this->reconstructStudent());

        $this->userRoles->shouldReceive('assign')
            ->once()
            ->with($user->requireId(), [RoleType::STUDENT]);

        $this->useCase()->execute($this->command());
    }

    public function test_throws_exception_when_email_already_exists(): void
    {
        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andThrow(UserAlreadyExistsException::class);

        $this->students->shouldNotReceive('save');
        $this->userRoles->shouldNotReceive('assign');

        $this->expectException(UserAlreadyExistsException::class);

        $this->useCase()->execute($this->command());
    }

    private function useCase(): CreateStudentUseCase
    {
        return new CreateStudentUseCase(
            $this->users,
            $this->userRoles,
            $this->students,
            $this->transaction,
        );
    }

    private function command(): CreateStudentCommand
    {
        return new CreateStudentCommand(
            email: $this->userEmail(),
            password: $this->userPassword(),
            name: $this->userName(),
            departmentId: $this->departmentId(),
            studentNumber: $this->studentNumber(),
        );
    }
}
