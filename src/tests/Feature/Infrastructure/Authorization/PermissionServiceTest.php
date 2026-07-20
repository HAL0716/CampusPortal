<?php

namespace Tests\Feature\Infrastructure\Authorization;

use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\PermissionType;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Models\Permission as PermissionModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class PermissionServiceTest extends TestCase
{
    use CreatesModelUser;
    use RefreshDatabase;

    private PermissionServiceInterface $permissions;

    private UserRepositoryInterface $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = $this->app->make(PermissionServiceInterface::class);
        $this->users = $this->app->make(UserRepositoryInterface::class);
    }

    public function test_returns_user_permissions(): void
    {
        $model = $this->createUser();

        $this->givePermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->users->findById(new UserId($model->id));

        $permissions = $this->permissions
            ->permissions($user);

        $this->assertSame(
            [
                PermissionType::DashboardView->value,
            ],
            $permissions
        );
    }

    public function test_returns_true_when_user_has_permission(): void
    {
        $model = $this->createUser();

        $this->givePermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->users->findById(new UserId($model->id));

        $this->assertTrue(
            $this->permissions->can(
                $user,
                PermissionType::DashboardView
            )
        );
    }

    public function test_returns_false_when_user_does_not_have_permission(): void
    {
        $model = $this->createUser();

        $user = $this->users->findById(new UserId($model->id));

        $this->assertFalse(
            $this->permissions->can(
                $user,
                PermissionType::DashboardView
            )
        );
    }

    public function test_caches_permissions(): void
    {
        $model = $this->createUser();

        $this->givePermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->users->findById(new UserId($model->id));

        $first = $this->permissions->permissions($user);

        // DBからPermissionを削除
        PermissionModel::query()->delete();

        $second = $this->permissions->permissions($user);

        $this->assertSame(
            $first,
            $second
        );
    }
}
