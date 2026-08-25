<?php

namespace Tests\Unit\Infrastructure\Authorization;

use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Infrastructure\Authorization\LaravelPermissionAuthorizationService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\TestHelpers\PermissionTestHelper;
use Tests\Support\TestHelpers\UserTestHelper;
use Tests\TestCase;

final class LaravelPermissionAuthorizationServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use PermissionTestHelper;
    use UserTestHelper;

    private PermissionRepository&MockInterface $permissions;

    private LaravelPermissionAuthorizationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = Mockery::mock(PermissionRepository::class);

        $this->service = new LaravelPermissionAuthorizationService(
            $this->permissions,
        );
    }

    public function test_can_get_permissions(): void
    {
        $user = $this->reconstructUser();
        $permission = $this->reconstructPermission(name: PermissionType::DashboardView);

        $this->permissions->shouldReceive('findByUser')
            ->once()
            ->with($user)
            ->andReturn([$permission]);

        $this->assertSame([PermissionType::DashboardView->value], $this->service->permissions($user));
    }

    public function test_can_remove_duplicate_permissions(): void
    {
        $user = $this->reconstructUser();
        $permission1 = $this->reconstructPermission(name: PermissionType::DashboardView);
        $permission2 = $this->reconstructPermission(id: 2, name: PermissionType::DashboardView);

        $this->permissions->shouldReceive('findByUser')
            ->once()
            ->with($user)
            ->andReturn([$permission1, $permission2]);

        $this->assertSame([PermissionType::DashboardView->value], $this->service->permissions($user));
    }

    public function test_can_check_existing_permission(): void
    {
        $user = $this->reconstructUser();
        $permission = $this->reconstructPermission(name: PermissionType::DashboardView);

        $this->permissions->shouldReceive('findByUser')
            ->once()
            ->with($user)
            ->andReturn([$permission]);

        $this->assertTrue($this->service->can($user, PermissionType::DashboardView));
    }

    public function test_cannot_check_missing_permission(): void
    {
        $user = $this->reconstructUser();
        $permission = $this->reconstructPermission(name: PermissionType::DashboardView);

        $this->permissions->shouldReceive('findByUser')
            ->once()
            ->with($user)
            ->andReturn([$permission]);

        $this->assertFalse($this->service->can($user, PermissionType::CourseOfferingView));
    }

    public function test_can_cache_permissions(): void
    {
        $user = $this->reconstructUser();
        $permission = $this->reconstructPermission(name: PermissionType::DashboardView);

        $this->permissions->shouldReceive('findByUser')
            ->once()
            ->with($user)
            ->andReturn([$permission]);

        $this->service->permissions($user);

        $this->assertSame([PermissionType::DashboardView->value], $this->service->permissions($user));
    }

    public function test_can_cache_permissions_when_checking_permission(): void
    {
        $user = $this->reconstructUser();
        $permission = $this->reconstructPermission(name: PermissionType::DashboardView);

        $this->permissions->shouldReceive('findByUser')
            ->once()
            ->with($user)
            ->andReturn([$permission]);

        $this->assertTrue($this->service->can($user, PermissionType::DashboardView));
        $this->assertTrue($this->service->can($user, PermissionType::DashboardView));
    }
}
