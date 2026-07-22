<?php

namespace App\Domain\Enrollment;

enum EnrollmentStatus: string
{
    case ENROLLED = 'enrolled';
    case DROPPED = 'dropped';
    case COMPLETED = 'completed';
}
