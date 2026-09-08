<?php

namespace Tests\Unit\Application\Contexts\User;

use App\Application\Contexts\User\Commands\UserCreateCommand;
use App\Application\Contexts\User\UseCases\UserCreateUseCase;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Repositories\UserRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\UserTestHelper;

final class UserCreateUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use UserTestHelper;

    private UserRepository&MockInterface $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepository::class);
    }

    public function test_creates_user(): void
    {
        $this->users->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturn($this->reconstructUser());

        $user = $this->useCase()->execute($this->command());

        $this->assertSame($this->userId()->value(), $user->id()->value());
        $this->assertSame($this->userEmail()->value(), $user->email()->value());
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
            email: $this->userEmail()->value(),
            password: $this->userPassword()->value(),
            name: $this->userName(),
        );
    }
}
