<?php

namespace App\Application\Contexts\CourseOffering\Show\Queries;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;

final readonly class GetCourseOfferingQuery
{
    public function __construct(
        public CourseOfferingId $courseOfferingId,
    ) {}
}
