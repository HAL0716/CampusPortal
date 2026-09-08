<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Role\Enums\RoleType;
use App\Infrastructure\Repositories\EloquentPermissionRepository;
use App\Models\Permission as PermissionModel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\PermissionTestHelper;
use Tests\TestCase;

final class EloquentPermissionRepositoryTest extends TestCase
{
    use PermissionTestHelper;
    use RefreshDatabase;

    private function repository(): EloquentPermissionRepository
    {
        return app(EloquentPermissionRepository::class);
    }

    public function test_find_by_user_id_returns_permissions(): void
    {
        $permissionTypes = [
            PermissionType::DashboardView,
            PermissionType::MaterialView,
        ];

        $permissions = array_map(
            fn (PermissionType $type): PermissionModel => PermissionModel::factory()->withName($type)->create(),
            $permissionTypes,
        );

        $user = User::factory()
            ->withRoles([Role::factory()->withPermissions($permissions)->create()])
            ->create();

        $result = $this->repository()->findByUserId($this->userId($user->id));

        self::assertSame(
            $permissionTypes,
            array_map(fn (Permission $permission): PermissionType => $permission->name(), $result),
        );
    }

    public function test_find_by_user_id_returns_unique_permissions_from_multiple_roles(): void
    {
        $permissionTypes = [
            PermissionType::DashboardView,
            PermissionType::MaterialView,
        ];

        $permissions = array_map(
            fn (PermissionType $type): PermissionModel => PermissionModel::factory()->withName($type)->create(),
            $permissionTypes,
        );

        $roles = [
            Role::factory()->withName(RoleType::ADMIN)->withPermissions($permissions)->create(),
            Role::factory()->withName(RoleType::TEACHER)->withPermissions([$permissions[0]])->create(),
        ];

        $user = User::factory()->withRoles($roles)->create();

        $result = $this->repository()->findByUserId($this->userId($user->id));

        self::assertSame(
            $permissionTypes,
            array_map(fn (Permission $permission): PermissionType => $permission->name(), $result),
        );
    }

    public function test_find_by_user_id_returns_empty_array_when_user_has_no_roles(): void
    {
        $user = User::factory()->create();

        self::assertSame([], $this->repository()->findByUserId($this->userId($user->id)));
    }

    public function test_find_by_user_id_returns_empty_array_when_user_does_not_exist(): void
    {
        self::assertSame([], $this->repository()->findByUserId($this->userId(999999)));
    }
}
