<?php

namespace App\Infrastructure\Clock;

use App\Application\Clock\ClockInterface;
use Carbon\CarbonImmutable;

final readonly class SystemClock implements ClockInterface
{
    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now();
    }
}
