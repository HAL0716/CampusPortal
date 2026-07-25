<?php

namespace App\Domain\CourseOffering;

interface CourseOfferingRepositoryInterface
{
    public function findById(CourseOfferingId $courseOfferingId): ?CourseOffering;
}
