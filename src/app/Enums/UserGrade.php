<?php

namespace App\Enums;

enum UserGrade: int
{
    case B1 = 1;
    case B2 = 2;
    case B3 = 3;
    case B4 = 4;
    case M1 = 5;
    case M2 = 6;
    case D1 = 7;
    case D2 = 8;
    case D3 = 9;

    public function label(): string
    {
        return match ($this) {
            self::B1 => 'B1',
            self::B2 => 'B2',
            self::B3 => 'B3',
            self::B4 => 'B4',
            self::M1 => 'M1',
            self::M2 => 'M2',
            self::D1 => 'D1',
            self::D2 => 'D2',
            self::D3 => 'D3',
        };
    }
}
