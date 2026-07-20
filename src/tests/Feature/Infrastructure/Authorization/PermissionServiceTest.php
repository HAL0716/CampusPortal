<?php

namespace Tests\Feature\Infrastructure\Authorization;

use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\PermissionType;
use App\Models\Permission as PermissionModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Permission\CreatesModelPermission;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class PermissionServiceTest extends TestCase
{
    use CreatesModelPermission;
    use CreatesModelUser;
    use RefreshDatabase;

    private PermissionServiceInterface $permissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = $this->app->make(PermissionServiceInterface::class);
    }

    public function test_returns_user_permissions(): void
    {
        $model = $this->createUser();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $permissions = $this->permissions->permissions($this->toDomainUser($model));

        $this->assertSame([PermissionType::DashboardView->value], $permissions);
    }

    public function test_returns_true_when_user_has_permission(): void
    {
        $model = $this->createUser();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $this->assertTrue(
            $this->permissions->can(
                $this->toDomainUser($model),
                PermissionType::DashboardView
            )
        );
    }

    public function test_returns_false_when_user_does_not_have_permission(): void
    {
        $model = $this->createUser();

        $this->assertFalse(
            $this->permissions->can(
                $this->toDomainUser($model),
                PermissionType::DashboardView
            )
        );
    }

    public function test_caches_permissions(): void
    {
        $model = $this->createUser();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->toDomainUser($model);

        $first = $this->permissions->permissions($user);

        // DBからPermissionを削除
        PermissionModel::query()->delete();

        $second = $this->permissions->permissions($user);

        $this->assertSame($first, $second);
    }
}
