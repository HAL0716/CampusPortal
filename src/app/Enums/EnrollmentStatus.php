<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case REGISTERED = 'registered';
    case DROPPED = 'withdrawn';
    case COMPLETED = 'completed';

    public static function values(): array
    {
        return array_map(fn (self $degree) => $degree->value, self::cases());
    }
}
