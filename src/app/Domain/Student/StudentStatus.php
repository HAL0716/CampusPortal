<?php

namespace App\Domain\Student;

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case EXPELLED = 'expelled';
    case GRADUATED = 'graduated';
}
