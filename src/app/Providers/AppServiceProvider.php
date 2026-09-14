<?php

namespace App\Providers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;
use App\Application\Services\Authorization\PermissionAuthorizationService;
use App\Application\Services\Database\Transaction;
use App\Application\Services\Security\PasswordHasher;
use App\Application\Services\Storage\FileStorage;
use App\Domain\Student\Policies\GraduationPolicy;
use App\Infrastructure\Authentication\LaravelAuthenticationService;
use App\Infrastructure\Authorization\LaravelCourseOfferingAuthorizationService;
use App\Infrastructure\Authorization\LaravelPermissionAuthorizationService;
use App\Infrastructure\Database\LaravelTransaction;
use App\Infrastructure\Security\LaravelPasswordHasher;
use App\Infrastructure\Storage\LaravelFileStorage;
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
        $this->app->bind(CourseOfferingAuthorizationService::class, LaravelCourseOfferingAuthorizationService::class);
        $this->app->bind(PasswordHasher::class, LaravelPasswordHasher::class);
        $this->app->bind(FileStorage::class, LaravelFileStorage::class);
        $this->app->bind(Transaction::class, LaravelTransaction::class);
        $this->app->singleton(GraduationPolicy::class, function () {
            return new GraduationPolicy(
                requiredCredits: config('student.graduation.required_credits'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
