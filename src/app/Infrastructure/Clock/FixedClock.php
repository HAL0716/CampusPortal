<?php

namespace App\Infrastructure\Clock;

use App\Application\Clock\ClockInterface;
use Carbon\CarbonImmutable;

final readonly class FixedClock implements ClockInterface
{
    public function __construct(
        private CarbonImmutable $now,
    ) {}

    public function now(): CarbonImmutable
    {
        return $this->now;
    }
}
