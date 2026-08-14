<?php

namespace App\Application\Enrollment;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;

final readonly class EnrollCommand
{
    public function __construct(
        public UserId $userId,
        public CourseOfferingId $courseOfferingId
    ) {}
}
