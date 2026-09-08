<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Role\Enums\RoleType;
use App\Domain\Role\Exceptions\RoleNotFoundException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\Repositories\EloquentUserRoleRepository;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentUserRoleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private function repository(): EloquentUserRoleRepository
    {
        return app(EloquentUserRoleRepository::class);
    }

    public function test_assign_assigns_role_to_user(): void
    {
        $user = UserModel::factory()->create();
        $role = RoleModel::factory()->withName(RoleType::ADMIN)->create();

        $this->repository()->assign(
            userId: new UserId($user->id),
            roles: [RoleType::ADMIN],
        );

        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function test_assign_assigns_multiple_roles_to_user(): void
    {
        $user = UserModel::factory()->create();
        $role1 = RoleModel::factory()->withName(RoleType::ADMIN)->create();
        $role2 = RoleModel::factory()->withName(RoleType::STUDENT)->create();

        $this->repository()->assign(
            userId: new UserId($user->id),
            roles: [RoleType::ADMIN, RoleType::STUDENT],
        );

        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $role1->id,
        ]);

        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $role2->id,
        ]);
    }

    public function test_assign_does_not_detach_existing_roles(): void
    {
        $user = UserModel::factory()->create();
        $existingRole = RoleModel::factory()->withName(RoleType::STUDENT)->create();
        $newRole = RoleModel::factory()->withName(RoleType::ADMIN)->create();

        $user->roles()->attach($existingRole->id);

        $this->repository()->assign(
            userId: new UserId($user->id),
            roles: [RoleType::ADMIN],
        );

        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $existingRole->id,
        ]);

        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $newRole->id,
        ]);
    }

    public function test_assign_does_not_create_duplicate_role_assignment(): void
    {
        $user = UserModel::factory()->create();
        $role = RoleModel::factory()->withName(RoleType::ADMIN)->create();

        $user->roles()->attach($role->id);

        $this->repository()->assign(
            userId: new UserId($user->id),
            roles: [RoleType::ADMIN],
        );

        $this->assertDatabaseCount('user_role', 1);
        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function test_assign_throws_exception_when_user_not_found(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->repository()->assign(
            userId: new UserId(999999),
            roles: [RoleType::ADMIN],
        );
    }

    public function test_assign_throws_exception_when_role_not_found(): void
    {
        $user = UserModel::factory()->create();

        $this->expectException(RoleNotFoundException::class);

        $this->repository()->assign(
            userId: new UserId($user->id),
            roles: [RoleType::ADMIN],
        );
    }

    public function test_assign_assigns_existing_roles_before_role_not_found_exception(): void
    {
        $user = UserModel::factory()->create();
        $existingRole = RoleModel::factory()->withName(RoleType::ADMIN)->create();

        $this->expectException(RoleNotFoundException::class);

        $this->repository()->assign(
            userId: new UserId($user->id),
            roles: [RoleType::ADMIN, RoleType::STUDENT],
        );

        $this->assertDatabaseHas('user_role', [
            'user_id' => $user->id,
            'role_id' => $existingRole->id,
        ]);
    }
}
