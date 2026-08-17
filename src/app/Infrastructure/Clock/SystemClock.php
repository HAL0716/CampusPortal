<?php

namespace App\Infrastructure\Clock;

use App\Application\Services\Clock\Clock;
use Carbon\CarbonImmutable;

final readonly class SystemClock implements Clock
{
    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now();
    }
}
