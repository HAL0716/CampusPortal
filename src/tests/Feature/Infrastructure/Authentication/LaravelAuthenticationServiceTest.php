<?php

namespace Tests\Feature\Infrastructure\Authentication;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class LaravelAuthenticationServiceTest extends TestCase
{
    use CreatesModelUser;
    use RefreshDatabase;

    private AuthenticationService $auth;

    private UserRepository $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth = $this->app->make(AuthenticationService::class);
        $this->users = $this->app->make(UserRepository::class);
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

    public function test_require_user_returns_authenticated_user(): void
    {
        $model = $this->createUser();

        $this->actingAs($model);

        $user = $this->auth->requireUser();

        $this->assertSame($model->id, $user->id()->value());
        $this->assertSame($model->email, $user->email()->value());
        $this->assertSame($model->name, $user->name());
    }

    public function test_require_user_throws_when_guest(): void
    {
        $this->expectException(AuthenticationFailedException::class);

        $this->auth->requireUser();
    }

    public function test_returns_null_and_logs_out_when_authenticated_user_no_longer_exists(): void
    {
        $model = $this->createUser();

        $this->actingAs($model);

        $model->delete();

        $this->assertNull($this->auth->user());
        $this->assertGuest();
    }

    public function test_user_returns_same_instance(): void
    {
        $model = $this->createUser();

        $this->actingAs($model);

        $first = $this->auth->user();
        $second = $this->auth->user();

        $this->assertSame($first, $second);
    }
}
