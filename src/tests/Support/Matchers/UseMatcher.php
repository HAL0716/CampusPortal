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

    private function idsMatcher(object ...$expected): Closure
    {
        return fn (...$actual) => count($actual) === count($expected)
            && collect($actual)->zip($expected)->every(
                fn ($pair) => $pair[0]->value() === $pair[1]->value()
            );
    }

    private function dateMatcher(CarbonImmutable $expected): Closure
    {
        return fn ($date) => $date->equalTo($expected);
    }
}
