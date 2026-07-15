<?php

namespace Tests\Unit\Application\User;

use App\Application\User\UserCreateCommand;
use App\Application\User\UserCreateUseCase;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\User\CreatesDomainUser;

final class UserCreateUseCaseTest extends TestCase
{
    use CreatesDomainUser;
    use MockeryPHPUnitIntegration;

    private UserRepositoryInterface&MockInterface $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepositoryInterface::class);
    }

    public function test_creates_user(): void
    {
        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturn($this->reconstructUser());

        $user = $this->useCase()->execute($this->command());

        $this->assertSame($this->userId(), $user->id()->value());
        $this->assertSame($this->userEmail(), $user->email()->value());
        $this->assertSame($this->userName(), $user->name());
    }

    public function test_throws_exception_when_email_already_exists(): void
    {
        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andThrow(UserAlreadyExistsException::class);

        $this->expectException(UserAlreadyExistsException::class);

        $this->useCase()->execute($this->command());
    }

    private function useCase(): UserCreateUseCase
    {
        return new UserCreateUseCase($this->users);
    }

    private function command(): UserCreateCommand
    {
        return new UserCreateCommand(
            email: $this->userEmail(),
            password: $this->userPassword(),
            name: $this->userName(),
        );
    }
}
