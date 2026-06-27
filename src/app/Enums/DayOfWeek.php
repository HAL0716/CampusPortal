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

    public function label(): string
    {
        return match ($this) {
            self::MONDAY => '月',
            self::TUESDAY => '火',
            self::WEDNESDAY => '水',
            self::THURSDAY => '木',
            self::FRIDAY => '金',
            self::INTENSIVE => '集中',
        };
    }
}
