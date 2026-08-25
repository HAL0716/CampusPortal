<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Permission\CreatesModelPermission;
use Tests\Support\TestHelpers\UserTestHelper;
use Tests\TestCase;

final class EloquentPermissionRepositoryTest extends TestCase
{
    use CreatesModelPermission;
    use RefreshDatabase;
    use UserTestHelper;

    private PermissionRepository $permissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = $this->app->make(PermissionRepository::class);
    }

    public function test_finds_permissions_by_user(): void
    {
        $model = User::factory()->create();

        $this->createPermission($model, PermissionType::DashboardView);

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $permissions = $this->permissions->findByUser($user);

        $this->assertCount(1, $permissions);
        $this->assertSame(PermissionType::DashboardView, $permissions[0]->name());
    }

    public function test_returns_empty_array_when_user_has_no_permissions(): void
    {
        $model = User::factory()->create();

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $permissions = $this->permissions->findByUser($user);

        $this->assertEmpty($permissions);
    }

    public function test_returns_domain_permission_entities(): void
    {
        $model = User::factory()->create();

        $this->createPermission($model, PermissionType::DashboardView);

        $user = $this->reconstructUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password
        );

        $permissions = $this->permissions->findByUser($user);

        $this->assertInstanceOf(Permission::class, $permissions[0]);
    }
}
