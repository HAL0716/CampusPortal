<?php

namespace App\Support;

use Carbon\Carbon;

class SystemClock
{
    private static ?int $offset = null;

    public static function initialize(): void
    {
        $time = config('app.time');

        if ($time) {
            self::$offset = Carbon::parse($time)->timestamp - time();
        }
    }

    public static function now(): Carbon
    {
        return Carbon::now()->addSeconds(self::$offset ?? 0);
    }
}
