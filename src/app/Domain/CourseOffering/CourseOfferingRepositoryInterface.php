<?php

namespace App\Domain\CourseOffering;

use App\Domain\CourseOffering\Entities\CourseOffering;

interface CourseOfferingRepositoryInterface
{
    public function findById(CourseOfferingId $courseOfferingId): ?CourseOffering;
}
