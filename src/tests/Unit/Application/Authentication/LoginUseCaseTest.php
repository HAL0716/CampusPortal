<?php

namespace Tests\Unit\Application\Authentication;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Authentication\Commands\LoginCommand;
use App\Application\Contexts\Authentication\UseCases\LoginUseCase;
use App\Application\Services\Security\PasswordHasher;
use App\Domain\Authentication\Exceptions\AuthenticationFailedException;
use App\Domain\User\Entities\User;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserEmail;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\UserTestHelper;

final class LoginUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use UserTestHelper;

    private UserRepository&MockInterface $users;

    private AuthenticationService&MockInterface $auth;

    private PasswordHasher&MockInterface $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepository::class);
        $this->auth = Mockery::mock(AuthenticationService::class);
        $this->hasher = Mockery::mock(PasswordHasher::class);
    }

    public function test_can_login_with_active_user(): void
    {
        $user = $this->reconstructUser(
            status: UserStatus::ACTIVE,
        );

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

        $this->hasher->shouldNotReceive('verify');
        $this->auth->shouldNotReceive('login');

        $this->expectException(AuthenticationFailedException::class);

        $this->useCase()->execute($this->command());
    }

    public function test_throws_exception_when_user_is_inactive(): void
    {
        $user = $this->reconstructUser(
            status: UserStatus::INACTIVE,
        );

        $this->expectUserLookup($user);

        $this->hasher->shouldNotReceive('verify');
        $this->auth->shouldNotReceive('login');

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
                fn (UserEmail $email) => $email->value() === $this->userEmail()->value()
            ))
            ->andReturn($user);
    }

    private function expectPasswordVerification(User $user, bool $isValid): void
    {
        $this->hasher->shouldReceive('verify')
            ->once()
            ->with($this->userPassword()->value(), $user->password()->value())
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
            email: $this->userEmail()->value(),
            password: $this->userPassword()->value()
        );
    }
}
