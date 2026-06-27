<?php

namespace App\Enums;

enum Term: string
{
    case FIRST = 'first';
    case SECOND = 'second';
    case THIRD = 'third';

    public static function values(): array
    {
        return array_map(fn (self $term) => $term->value, self::cases());
    }

    public function index(): int
    {
        return match ($this) {
            self::FIRST => 1,
            self::SECOND => 2,
            self::THIRD => 3,
        };
    }
}
