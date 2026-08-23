<?php

namespace App\Http\Flash;

use Illuminate\Support\Str;

final class Flash
{
    public static function success(string $message): array
    {
        return self::make('success', $message);
    }

    public static function error(string $message): array
    {
        return self::make('error', $message);
    }

    private static function make(string $type, string $message): array
    {
        return [$type => ['id' => Str::uuid()->toString(), 'message' => $message]];
    }
}
