<?php

namespace Tests\Feature\Infrastructure\Auth;

use App\Application\Auth\AuthenticationServiceInterface;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class AuthenticationServiceTest extends TestCase
{
    use CreatesModelUser;
    use RefreshDatabase;

    private AuthenticationServiceInterface $auth;

    private UserRepositoryInterface $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth = $this->app->make(AuthenticationServiceInterface::class);
        $this->users = $this->app->make(UserRepositoryInterface::class);
    }

    public function test_login_user(): void
    {
        $model = $this->createUser();

        $user = $this->users->findById(new UserId($model->id));

        $this->assertNotNull($user);

        $this->auth->login($user);

        $this->assertAuthenticatedAs($model);
    }

    public function test_logout_user(): void
    {
        $this->actingAs($this->createUser());

        $this->auth->logout();

        $this->assertGuest();
    }

    public function test_returns_authenticated_user(): void
    {
        $model = $this->createUser();

        $this->actingAs($model);

        $user = $this->auth->user();

        $this->assertNotNull($user);
        $this->assertSame($model->id, $user->id()->value());
        $this->assertSame($model->email, $user->email()->value());
        $this->assertSame($model->name, $user->name());
    }
}
