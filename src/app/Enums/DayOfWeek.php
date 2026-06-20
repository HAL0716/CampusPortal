<?php

namespace App\Enums;

enum DayOfWeek: string
{
    case MONDAY = 'Mon';
    case TUESDAY = 'Tue';
    case WEDNESDAY = 'Wed';
    case THURSDAY = 'Thu';
    case FRIDAY = 'Fri';
    case INTENSIVE = 'Intensive';

    public static function values(): array
    {
        return array_map(fn (self $dayOfWeek) => $dayOfWeek->value, self::cases());
    }
}
