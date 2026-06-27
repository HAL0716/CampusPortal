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

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => '履修',
            self::DROPPED => '取消',
            self::COMPLETED => '完了',
        };
    }
}
