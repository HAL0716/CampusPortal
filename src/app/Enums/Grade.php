<?php

namespace App\Enums;

enum Grade: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
    case F = 'F';

    public static function values(): array
    {
        return array_map(fn (Grade $grade) => $grade->value, self::cases());
    }
}
