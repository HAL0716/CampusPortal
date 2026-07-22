<?php

namespace App\Application\CourseOffering;

use App\Domain\Academic\Term;

final readonly class ListCourseOfferingsQuery
{
    public function __construct(
        public string $academicYear,
        public Term $term,
    ) {}
}
