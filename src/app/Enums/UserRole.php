<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
