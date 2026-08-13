<?php

namespace App\Application\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\User\UserId;

final readonly class DropCommand
{
    public function __construct(
        public UserId $userId,
        public CourseOfferingId $courseOfferingId
    ) {}
}
