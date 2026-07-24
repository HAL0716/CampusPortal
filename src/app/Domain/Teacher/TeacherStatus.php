<?php

namespace App\Domain\Teacher;

enum TeacherStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case RETIRED = 'retired';
}
