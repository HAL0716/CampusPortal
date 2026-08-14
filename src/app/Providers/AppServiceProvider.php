<?php

namespace App\Providers;

use App\Application\Contexts\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Services\Authentication\AuthenticationService;
use App\Application\Services\Authorization\EnrollmentAuthorizationService;
use App\Application\Services\Authorization\PermissionAuthorizationService;
use App\Application\Services\Security\PasswordHasher;
use App\Infrastructure\Authentication\LaravelAuthenticationService;
use App\Infrastructure\Authorization\LaravelEnrollmentAuthorizationService;
use App\Infrastructure\Authorization\LaravelPermissionAuthorizationService;
use App\Infrastructure\QueryServices\EloquentCourseOfferingQueryService;
use App\Infrastructure\Security\LaravelPasswordHasher;
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
        $this->app->bind(CourseOfferingQueryServiceInterface::class, EloquentCourseOfferingQueryService::class);
        $this->app->bind(PasswordHasher::class, LaravelPasswordHasher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
