<?php

namespace App\Application\Contexts\CourseOffering\Management;

use App\Domain\User\ValueObjects\UserId;
use Carbon\CarbonImmutable;

final readonly class ListCourseOfferingsQuery
{
    public function __construct(
        public CarbonImmutable $date,
        public UserId $userId,
    ) {}
}
