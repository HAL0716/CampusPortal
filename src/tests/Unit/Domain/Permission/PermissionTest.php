<?php

namespace Tests\Unit\Domain\Permission;

use PHPUnit\Framework\TestCase;
use Tests\Support\Permission\CreatesDomainPermission;

final class PermissionTest extends TestCase
{
    use CreatesDomainPermission;

    public function test_creates_valid_permission(): void
    {
        $permission = $this->createPermission();

        $this->assertNull($permission->id());
        $this->assertSame($this->permissionName(), $permission->name());
    }

    public function test_reconstructs_valid_permission(): void
    {
        $permission = $this->reconstructPermission();

        $this->assertSame($this->permissionId(), $permission->id()->value());
        $this->assertSame($this->permissionName(), $permission->name());
    }
}
