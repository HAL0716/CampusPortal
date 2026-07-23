<?php

namespace App\Application\CourseOffering;

use App\Domain\Academic\Term;
use App\Domain\User\UserId;

final readonly class ListCourseOfferingsQuery
{
    public function __construct(
        public string $academicYear,
        public Term $term,
        public UserId $userId
    ) {}
}
