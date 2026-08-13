<?php

namespace App\Domain\Teacher\Enums;

enum TeacherStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case RETIRED = 'retired';
}
