<?php

namespace App\Providers;

use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Infrastructure\QueryServices\EloquentCourseOfferingQueryService;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    private const QUERY_SERVICES = [
        CourseOfferingQueryService::class => EloquentCourseOfferingQueryService::class,
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
