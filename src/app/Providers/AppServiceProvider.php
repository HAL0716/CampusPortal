<?php

namespace App\Providers;

use App\Application\Auth\AuthenticationServiceInterface;
use App\Application\User\UserDuplicateDetectorInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Auth\AuthenticationService;
use App\Infrastructure\Database\MysqlUserDuplicateDetector;
use App\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(
            UserDuplicateDetectorInterface::class,
            fn () => new MysqlUserDuplicateDetector(config('user.constraints'))
        );

        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
