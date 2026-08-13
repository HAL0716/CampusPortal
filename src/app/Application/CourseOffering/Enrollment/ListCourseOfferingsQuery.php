<?php

namespace App\Application\CourseOffering\Enrollment;

use App\Domain\User\UserId;
use Carbon\CarbonImmutable;

final readonly class ListCourseOfferingsQuery
{
    public function __construct(
        public CarbonImmutable $date,
        public UserId $userId,
    ) {}
}
