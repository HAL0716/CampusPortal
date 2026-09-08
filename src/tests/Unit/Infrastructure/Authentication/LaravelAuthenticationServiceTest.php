<?php

namespace Tests\Unit\Infrastructure\Authentication;

use App\Domain\Authentication\Exceptions\AuthenticationFailedException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\Authentication\LaravelAuthenticationService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Mockery\MockInterface;
use Tests\Support\TestHelpers\UserTestHelper;
use Tests\TestCase;

final class LaravelAuthenticationServiceTest extends TestCase
{
    use UserTestHelper;

    private UserRepository&MockInterface $users;

    private LaravelAuthenticationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = Mockery::mock(UserRepository::class);

        $this->service = new LaravelAuthenticationService(
            $this->users,
        );
    }

    public function test_can_login(): void
    {
        $user = $this->reconstructUser();

        Auth::shouldReceive('loginUsingId')
            ->once()
            ->with($user->requireId()->value())
            ->andReturnTrue();

        $this->service->login($user);

        self::assertSame($user, $this->service->user());
    }

    public function test_cannot_login_when_authentication_fails(): void
    {
        $user = $this->reconstructUser();

        Auth::shouldReceive('loginUsingId')
            ->once()
            ->with($user->requireId()->value())
            ->andReturnFalse();

        $this->expectException(AuthenticationFailedException::class);

        $this->service->login($user);
    }

    public function test_can_logout(): void
    {
        $user = $this->reconstructUser();

        Auth::shouldReceive('loginUsingId')
            ->once()
            ->with($user->requireId()->value())
            ->andReturnTrue();

        $this->service->login($user);

        Auth::shouldReceive('logout')
            ->once();

        $this->service->logout();

        Auth::shouldReceive('id')
            ->once()
            ->andReturnNull();

        self::assertNull($this->service->user());
    }

    public function test_can_get_authenticated_user(): void
    {
        $user = $this->reconstructUser();

        Auth::shouldReceive('id')
            ->once()
            ->andReturn($user->requireId()->value());

        $this->users
            ->shouldReceive('findById')
            ->once()
            ->withArgs(function (UserId $userId) use ($user): bool {
                return $userId->value() === $user->requireId()->value();
            })
            ->andReturn($user);

        self::assertSame($user, $this->service->user());
    }

    public function test_can_get_cached_user_without_querying_repository_again(): void
    {
        $user = $this->reconstructUser();

        Auth::shouldReceive('id')
            ->once()
            ->andReturn($user->requireId()->value());

        $this->users
            ->shouldReceive('findById')
            ->once()
            ->andReturn($user);

        self::assertSame($user, $this->service->user());
        self::assertSame($user, $this->service->user());
    }

    public function test_cannot_get_user_when_not_authenticated(): void
    {
        Auth::shouldReceive('id')
            ->once()
            ->andReturnNull();

        self::assertNull($this->service->user());
    }

    public function test_cannot_get_user_when_authenticated_user_does_not_exist(): void
    {
        Auth::shouldReceive('id')
            ->once()
            ->andReturn($this->userId()->value());

        $this->users
            ->shouldReceive('findById')
            ->once()
            ->withArgs(function (UserId $userId): bool {
                return $userId->value() === $this->userId()->value();
            })
            ->andReturnNull();

        Auth::shouldReceive('logout')
            ->once();

        self::assertNull($this->service->user());
    }

    public function test_can_require_authenticated_user(): void
    {
        $user = $this->reconstructUser();

        Auth::shouldReceive('id')
            ->once()
            ->andReturn($user->requireId()->value());

        $this->users
            ->shouldReceive('findById')
            ->once()
            ->andReturn($user);

        self::assertSame($user, $this->service->requireUser());
    }

    public function test_cannot_require_user_when_not_authenticated(): void
    {
        Auth::shouldReceive('id')
            ->once()
            ->andReturnNull();

        $this->expectException(AuthenticationFailedException::class);

        $this->service->requireUser();
    }
}
