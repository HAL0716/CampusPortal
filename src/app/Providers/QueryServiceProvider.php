<?php

namespace App\Providers;

use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Application\Contexts\Enrollment\Services\EnrollmentQueryService;
use App\Application\Contexts\Material\Services\MaterialQueryService;
use App\Application\Contexts\Student\Services\StudentQueryService;
use App\Infrastructure\QueryServices\EloquentCourseOfferingQueryService;
use App\Infrastructure\QueryServices\EloquentEnrollmentQueryService;
use App\Infrastructure\QueryServices\EloquentMaterialQueryService;
use App\Infrastructure\QueryServices\EloquentStudentQueryService;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    private const QUERY_SERVICES = [
        StudentQueryService::class => EloquentStudentQueryService::class,
        CourseOfferingQueryService::class => EloquentCourseOfferingQueryService::class,
        EnrollmentQueryService::class => EloquentEnrollmentQueryService::class,
        MaterialQueryService::class => EloquentMaterialQueryService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach (self::QUERY_SERVICES as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
