<?php

namespace Tests\Feature\Infrastructure\Authorization;

use App\Application\Services\Authorization\PermissionAuthorizationService;
use App\Domain\Permission\Enums\PermissionType;
use App\Models\Permission as PermissionModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Permission\CreatesModelPermission;
use Tests\Support\TestHelpers\UserTestHelper;
use Tests\TestCase;

final class LaravelPermissionAuthorizationServiceTest extends TestCase
{
    use CreatesModelPermission;
    use RefreshDatabase;
    use UserTestHelper;

    private PermissionAuthorizationService $permissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = $this->app->make(PermissionAuthorizationService::class);
    }

    public function test_returns_user_permissions(): void
    {
        $model = User::factory()->create();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $permissions = $this->permissions->permissions($user);

        $this->assertSame([PermissionType::DashboardView->value], $permissions);
    }

    public function test_returns_true_when_user_has_permission(): void
    {
        $model = User::factory()->create();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $this->assertTrue($this->permissions->can($user, PermissionType::DashboardView));
    }

    public function test_returns_false_when_user_does_not_have_permission(): void
    {
        $model = User::factory()->create();

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $this->assertFalse($this->permissions->can($user, PermissionType::DashboardView));
    }

    public function test_caches_permissions(): void
    {
        $model = User::factory()->create();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $first = $this->permissions->permissions($user);

        // DBからPermissionを削除
        PermissionModel::query()->delete();

        $second = $this->permissions->permissions($user);

        $this->assertSame($first, $second);
    }
}
