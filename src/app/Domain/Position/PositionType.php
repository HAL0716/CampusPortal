<?php

namespace App\Domain\Position;

enum PositionType: string
{
    case PROFESSOR = '教授';
    case ASSOCIATE = '准教授';
    case ASSISTANT = '助教';
}
