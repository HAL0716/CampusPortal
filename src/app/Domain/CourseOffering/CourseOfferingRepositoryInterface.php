<?php

namespace App\Domain\CourseOffering;

use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;

interface CourseOfferingRepositoryInterface
{
    public function findById(CourseOfferingId $courseOfferingId): ?CourseOffering;
}
