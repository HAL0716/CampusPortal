<?php

namespace Tests\Support\Clock;

use App\Application\Services\Clock\Clock;
use Carbon\CarbonImmutable;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait UseClock
{
    protected function useClock(CarbonImmutable|string $date): void
    {
        config()->set('app.now', CarbonImmutable::parse($date)->toIso8601String());

        app()->forgetInstance(Clock::class);
    }
}
