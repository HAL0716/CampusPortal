<?php

namespace Tests\Unit\Domain\Permission;

use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Permission\Exceptions\PermissionIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\PermissionTestHelper;

final class PermissionTest extends TestCase
{
    use PermissionTestHelper;

    public function test_create_returns_permission_without_id(): void
    {
        $permission = $this->createPermission();

        $this->assertNull($permission->id());
        $this->assertSame(PermissionType::DashboardView, $permission->name());
    }

    public function test_reconstruct_restores_permission_with_id(): void
    {
        $permission = $this->reconstructPermission();

        $this->assertSame($this->permissionId()->value(), $permission->id()->value());
        $this->assertSame(PermissionType::DashboardView, $permission->name());
    }

    public function test_require_id_returns_assigned_id(): void
    {
        $permission = $this->reconstructPermission();

        $this->assertSame($this->permissionId()->value(), $permission->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(PermissionIdNotAssignedException::class);

        $this->createPermission()->requireId();
    }
}
