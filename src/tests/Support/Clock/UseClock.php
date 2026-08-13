<?php

namespace Tests\Support\Clock;

use App\Application\Clock\ClockInterface;
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

        app()->forgetInstance(ClockInterface::class);
    }
}
