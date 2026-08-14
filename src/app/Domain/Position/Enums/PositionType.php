<?php

namespace App\Domain\Position\Enums;

enum PositionType: string
{
    case PROFESSOR = '教授';
    case ASSOCIATE = '准教授';
    case ASSISTANT = '助教';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
