<?php

namespace App\Domain\FinalGrade\Enums;

enum FinalGradeType: string
{
    case S = 'S';
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case F = 'F';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
