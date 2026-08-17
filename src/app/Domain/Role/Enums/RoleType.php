<?php

namespace App\Domain\Role\Enums;

enum RoleType: string
{
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case ADMIN = 'admin';
}
