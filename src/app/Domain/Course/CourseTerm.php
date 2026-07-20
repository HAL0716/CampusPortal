<?php

namespace App\Domain\Course;

enum CourseTerm: string
{
    case FIRST = '1';
    case SECOND = '2';
    case THIRD = '3';

    public static function getValues(): array
    {
        return array_map(fn (self $term) => $term->value, self::cases());
    }
}
