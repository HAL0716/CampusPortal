<?php

namespace App\Application\Contexts\Enrollment;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;

final readonly class DropCommand
{
    public function __construct(
        public UserId $userId,
        public CourseOfferingId $courseOfferingId
    ) {}
}
