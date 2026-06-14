<?php

namespace App\Enums;

enum TeacherStatus: string
{
    case ACTIVE = 'active';
    case RESIGNED = 'resigned';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
