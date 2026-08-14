<?php

namespace App\Providers;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Security\PasswordHasherInterface;
use App\Application\Services\Authentication\AuthenticationService;
use App\Application\Services\Authorization\EnrollmentAuthorizationService;
use App\Application\Services\Authorization\PermissionAuthorizationService;
use App\Infrastructure\Authentication\LaravelAuthenticationService;
use App\Infrastructure\Authorization\LaravelEnrollmentAuthorizationService;
use App\Infrastructure\Authorization\LaravelPermissionAuthorizationService;
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
        $this->app->scoped(AuthenticationService::class, LaravelAuthenticationService::class);
        $this->app->scoped(PermissionAuthorizationService::class, LaravelPermissionAuthorizationService::class);
        $this->app->bind(EnrollmentAuthorizationService::class, LaravelEnrollmentAuthorizationService::class);
        $this->app->bind(CourseOfferingQueryServiceInterface::class, CourseOfferingQueryService::class);
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
