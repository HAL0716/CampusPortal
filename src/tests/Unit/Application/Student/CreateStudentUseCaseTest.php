<?php

namespace Tests\Unit\Application\Contexts\Student;

use App\Application\Contexts\Student\Commands\CreateStudentCommand;
use App\Application\Contexts\Student\UseCases\CreateStudentUseCase;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Repositories\UserRepository;
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

    private StudentRepository&MockInterface $students;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepository::class);
        $this->students = Mockery::mock(StudentRepository::class);
    }

    public function test_creates_student(): void
    {
        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturn($this->reconstructUser());

        $this->students->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (Student $student): bool {
                return $student->userId()->value() === $this->userId()->value()
                    && $student->departmentId()->value() === $this->departmentId()->value();
            }))
            ->andReturn($this->reconstructStudent());

        $this->useCase()->execute($this->command());
    }

    public function test_throws_exception_when_email_already_exists(): void
    {
        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andThrow(UserAlreadyExistsException::class);

        $this->students->shouldNotReceive('save');

        $this->expectException(UserAlreadyExistsException::class);

        $this->useCase()->execute($this->command());
    }

    private function useCase(): CreateStudentUseCase
    {
        return new CreateStudentUseCase(
            $this->users,
            $this->students,
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
