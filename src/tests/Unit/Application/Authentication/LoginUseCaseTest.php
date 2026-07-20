<?php

namespace Tests\Unit\Application\Authentication;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Authentication\LoginCommand;
use App\Application\Authentication\LoginUseCase;
use App\Application\Security\PasswordHasherInterface;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\User;
use App\Domain\User\UserEmail;
use App\Domain\User\UserRepositoryInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\User\CreatesDomainUser;

final class LoginUseCaseTest extends TestCase
{
    use CreatesDomainUser;
    use MockeryPHPUnitIntegration;

    private UserRepositoryInterface&MockInterface $users;

    private AuthenticationServiceInterface&MockInterface $auth;

    private PasswordHasherInterface&MockInterface $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepositoryInterface::class);
        $this->auth = Mockery::mock(AuthenticationServiceInterface::class);
        $this->hasher = Mockery::mock(PasswordHasherInterface::class);
    }

    public function test_login(): void
    {
        $user = $this->reconstructUser();

        $this->expectUserLookup($user);
        $this->expectPasswordVerification($user, true);

        $this->auth->shouldReceive('login')
            ->once()
            ->with($user);

        $this->useCase()->execute($this->command());
    }

    public function test_throws_exception_when_user_not_found(): void
    {
        $this->expectUserLookup(null);

        $this->expectException(AuthenticationFailedException::class);

        $this->useCase()->execute($this->command());
    }

    public function test_throws_exception_when_password_is_invalid(): void
    {
        $user = $this->reconstructUser();

        $this->expectUserLookup($user);
        $this->expectPasswordVerification($user, false);

        $this->auth->shouldNotReceive('login');

        $this->expectException(AuthenticationFailedException::class);

        $this->useCase()->execute($this->command());
    }

    private function expectUserLookup(?User $user): void
    {
        $this->users->shouldReceive('findByEmail')
            ->once()
            ->with(Mockery::on(
                fn (UserEmail $email) => $email->value() === $this->userEmail()
            ))
            ->andReturn($user);
    }

    private function expectPasswordVerification(User $user, bool $isValid): void
    {
        $this->hasher->shouldReceive('verify')
            ->once()
            ->with($this->userPassword(), $user->password()->value())
            ->andReturn($isValid);
    }

    private function useCase(): LoginUseCase
    {
        return new LoginUseCase(
            $this->users,
            $this->auth,
            $this->hasher
        );
    }

    private function command(): LoginCommand
    {
        return new LoginCommand(
            email: $this->userEmail(),
            password: $this->userPassword()
        );
    }
}
