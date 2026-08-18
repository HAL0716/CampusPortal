<?php

namespace App\Application\Contexts\CourseOffering\Index\Enums;

enum CourseOfferingStatus: string
{
    // Default
    case NONE = 'none';

    // For Student
    case ENROLLED = 'enrolled';
    case DROPPED = 'dropped';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    // For Teacher
    case TEACHING = 'teaching';
}
