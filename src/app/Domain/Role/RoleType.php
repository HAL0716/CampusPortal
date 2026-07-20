<?php

namespace App\Domain\Role;

enum RoleType: string
{
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case ADMIN = 'admin';

    case TEST = 'test';
}
