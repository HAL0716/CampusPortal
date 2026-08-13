<?php

namespace App\Domain\Student\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case EXPELLED = 'expelled';
    case GRADUATED = 'graduated';
}
