<?php

namespace App\Infrastructure\Clock;

use App\Application\Services\Clock\Clock;
use Carbon\CarbonImmutable;

final readonly class FixedClock implements Clock
{
    public function __construct(
        private CarbonImmutable $now,
    ) {}

    public function now(): CarbonImmutable
    {
        return $this->now;
    }
}
