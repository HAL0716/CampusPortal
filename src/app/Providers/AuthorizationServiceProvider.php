<?php

namespace App\Providers;

use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\PermissionType;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (PermissionType::cases() as $permission) {
            Gate::define(
                $permission->value,
                function (UserModel $user) use ($permission): bool {
                    return app(PermissionServiceInterface::class)
                        ->can(
                            app(UserRepositoryInterface::class)
                                ->findById(new UserId($user->id)),
                            $permission
                        );
                }
            );
        }
    }
}
