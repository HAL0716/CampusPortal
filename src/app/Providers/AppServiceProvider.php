<?php

namespace App\Providers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Authorization\EnrollmentAuthorizationServiceInterface;
use App\Application\Authorization\PermissionServiceInterface;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Enrollment\EnrollmentDuplicateDetectorInterface;
use App\Application\FinalGrade\FinalGradeDuplicateDetectorInterface;
use App\Application\Security\PasswordHasherInterface;
use App\Application\User\UserDuplicateDetectorInterface;
use App\Infrastructure\Authentication\AuthenticationService;
use App\Infrastructure\Authorization\EnrollmentAuthorizationService;
use App\Infrastructure\Authorization\PermissionService;
use App\Infrastructure\Database\Mysql\MysqlEnrollmentDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlFinalGradeDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlUserDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteEnrollmentDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteFinalGradeDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteUserDuplicateDetector;
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
        $this->app->bind(
            UserDuplicateDetectorInterface::class,
            fn ($app) => match (config('database.default')) {
                'sqlite' => $app->make(SqliteUserDuplicateDetector::class),
                default => $app->make(MysqlUserDuplicateDetector::class),
            }
        );

        $this->app->bind(
            EnrollmentDuplicateDetectorInterface::class,
            fn ($app) => match (config('database.default')) {
                'sqlite' => $app->make(SqliteEnrollmentDuplicateDetector::class),
                default => $app->make(MysqlEnrollmentDuplicateDetector::class),
            }
        );

        $this->app->bind(
            FinalGradeDuplicateDetectorInterface::class,
            fn ($app) => match (config('database.default')) {
                'sqlite' => $app->make(SqliteFinalGradeDuplicateDetector::class),
                default => $app->make(MysqlFinalGradeDuplicateDetector::class),
            }
        );

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
