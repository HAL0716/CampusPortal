<?php

namespace App\Domain\Enrollment\Enums;

enum EnrollmentStatus: string
{
    case ENROLLED = 'enrolled';
    case DROPPED = 'dropped';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
