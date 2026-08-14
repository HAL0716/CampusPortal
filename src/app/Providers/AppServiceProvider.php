<?php

namespace App\Providers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Authorization\EnrollmentAuthorizationServiceInterface;
use App\Application\Authorization\PermissionServiceInterface;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Security\PasswordHasherInterface;
use App\Infrastructure\Authentication\AuthenticationService;
use App\Infrastructure\Authorization\EnrollmentAuthorizationService;
use App\Infrastructure\Authorization\PermissionService;
use App\Infrastructure\QueryServices\CourseOfferingQueryService;
use App\Infrastructure\Security\PasswordHasher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CourseOfferingQueryServiceInterface::class, CourseOfferingQueryService::class);

        $this->app->scoped(AuthenticationServiceInterface::class, AuthenticationService::class);

        $this->app->scoped(PermissionServiceInterface::class, PermissionService::class);

        $this->app->bind(EnrollmentAuthorizationServiceInterface::class, EnrollmentAuthorizationService::class);

        $this->app->bind(PasswordHasherInterface::class, PasswordHasher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
