<?php

namespace App\Infrastructure\Repositories;

use App\Domain\CourseOffering\CourseOfferingRepositoryInterface;
use App\Models\CourseOffering as CourseOfferingModel;

class CourseOfferingRepository implements CourseOfferingRepositoryInterface
{
    public function findAll(): array
    {
        return CourseOfferingModel::with('course')
            ->get()
            ->toArray();
    }
}
