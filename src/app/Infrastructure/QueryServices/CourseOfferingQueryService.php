<?php

namespace App\Infrastructure\QueryServices;

use App\Application\CourseOffering\CourseOfferingListDTO;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Semester\SemesterId;
use App\Models\CourseOffering;

final class CourseOfferingQueryService implements CourseOfferingQueryServiceInterface
{
    public function findBySemesterId(
        SemesterId $semesterId
    ): array {
        return CourseOffering::query()
            ->where('semester_id', $semesterId->value())
            ->with('course')
            ->get()
            ->map(
                fn (CourseOffering $offering) => new CourseOfferingListDTO(
                    id: $offering->id,
                    name: $offering->course->name,
                )
            )
            ->all();
    }
}
