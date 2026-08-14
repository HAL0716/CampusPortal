<?php

namespace App\Providers;

use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserId;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $permissions = app(PermissionServiceInterface::class);
        $users = app(UserRepository::class);

        foreach (PermissionType::cases() as $permission) {
            Gate::define($permission->value, fn (UserModel $user): bool => $permissions->can(
                $users->findById(new UserId($user->id)),
                $permission
            )
            );
        }
    }
}
