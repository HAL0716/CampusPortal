<?php

namespace App\Providers;

use App\Application\Clock\ClockInterface;
use App\Infrastructure\Clock\FixedClock;
use App\Infrastructure\Clock\SystemClock;
use Carbon\CarbonImmutable;
use Illuminate\Support\ServiceProvider;

class ClockServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ClockInterface::class, function () {
            $fixed = config('app.now');

            return $fixed === null
                ? new SystemClock
                : new FixedClock(CarbonImmutable::parse($fixed));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
