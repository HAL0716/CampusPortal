<?php

namespace App\Application\Contexts\CourseOffering\Administration;

use Carbon\CarbonImmutable;

final readonly class ListCourseOfferingsQuery
{
    public function __construct(
        public CarbonImmutable $date
    ) {}
}
