<?php

namespace App\Application\Contexts\CourseOffering\Show\Queries;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;

final readonly class GetCourseOfferingQuery
{
    public function __construct(
        public CourseOfferingId $courseOfferingId,
        public UserId $userId,
    ) {}
}
