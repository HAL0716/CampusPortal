<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Permission\Permission;
use App\Domain\Permission\PermissionRepositoryInterface;
use App\Domain\Permission\PermissionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Permission\CreatesModelPermission;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class PermissionRepositoryTest extends TestCase
{
    use CreatesModelPermission;
    use CreatesModelUser;
    use RefreshDatabase;

    private PermissionRepositoryInterface $permissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = $this->app->make(PermissionRepositoryInterface::class);
    }

    public function test_finds_permissions_by_user(): void
    {
        $model = $this->createUser();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $permissions = $this->permissions->findByUser($this->toDomainUser($model));

        $this->assertCount(1, $permissions);
        $this->assertSame(PermissionType::DashboardView, $permissions[0]->name());
    }

    public function test_returns_empty_array_when_user_has_no_permissions(): void
    {
        $model = $this->createUser();

        $permissions = $this->permissions->findByUser($this->toDomainUser($model));

        $this->assertEmpty($permissions);
    }

    public function test_returns_domain_permission_entities(): void
    {
        $model = $this->createUser();

        $this->createPermission(
            $model,
            PermissionType::DashboardView
        );

        $permissions = $this->permissions->findByUser($this->toDomainUser($model));

        $this->assertInstanceOf(Permission::class, $permissions[0]);
    }
}
