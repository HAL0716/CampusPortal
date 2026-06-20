<?php

namespace App\Enums;

enum Degree: string
{
    case BACHELOR = 'bachelor';
    case MASTER = 'master';
    case DOCTOR = 'doctor';

    public static function values(): array
    {
        return array_map(fn (self $degree) => $degree->value, self::cases());
    }
}
