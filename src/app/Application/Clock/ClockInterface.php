<?php

namespace App\Application\Clock;

use Carbon\CarbonImmutable;

interface ClockInterface
{
    public function now(): CarbonImmutable;
}
