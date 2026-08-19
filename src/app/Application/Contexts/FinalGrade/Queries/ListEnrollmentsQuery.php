<?php

namespace App\Application\Contexts\FinalGrade\Queries;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;

final readonly class ListEnrollmentsQuery
{
    public function __construct(
        public UserId $userId,
        public CourseOfferingId $courseOfferingId,
    ) {}
}
