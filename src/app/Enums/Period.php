<?php

namespace App\Enums;

enum Period: string
{
    case FIRST = '1';
    case SECOND = '2';
    case THIRD = '3';
    case FOURTH = '4';
    case FIFTH = '5';
    case SIXTH = '6';
    case INTENSIVE = 'Intensive';

    public static function values(): array
    {
        return array_map(fn (self $period) => $period->value, self::cases());
    }
}
