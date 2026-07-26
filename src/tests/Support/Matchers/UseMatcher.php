<?php

namespace Tests\Support\Matchers;

use Carbon\CarbonImmutable;
use Closure;

trait UseMatcher
{
    private function idMatcher(object $expected): Closure
    {
        return fn ($id) => $id->value() === $expected->value();
    }

    private function dateMatcher(CarbonImmutable $expected): Closure
    {
        return fn ($date) => $date->equalTo($expected);
    }
}
