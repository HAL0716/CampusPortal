<?php

namespace App\Domain\CourseOffering\Repositories;

use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;

interface CourseOfferingRepository
{
    public function findById(CourseOfferingId $courseOfferingId): ?CourseOffering;
}
