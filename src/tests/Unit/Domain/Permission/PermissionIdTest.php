<?php

namespace Tests\Unit\Domain\Permission;

use PHPUnit\Framework\TestCase;
use Tests\Support\Permission\CreatesDomainPermission;

final class PermissionIdTest extends TestCase
{
    use CreatesDomainPermission;

    public function test_creates_valid_permission_id(): void
    {
        $id = $this->permissionIdValueObject();

        $this->assertSame($this->permissionId(), $id->value());
    }
}
