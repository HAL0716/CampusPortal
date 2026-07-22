<?php

namespace App\Infrastructure\Repositories;

use App\Domain\CourseOffering\CourseOfferingRepositoryInterface;
use App\Domain\Student\StudentId;
use App\Models\CourseOffering as CourseOfferingModel;

class CourseOfferingRepository implements CourseOfferingRepositoryInterface
{
    public function findAll(): array
    {
        return CourseOfferingModel::with('course')
            ->get()
            ->toArray();
    }

    public function findAllForStudent(StudentId $studentId): array
    {
        return CourseOfferingModel::query()
            ->with('course')
            ->leftJoin('enrollments', function ($join) use ($studentId) {
                $join->on('course_offerings.id', '=', 'enrollments.course_offering_id')
                    ->where('enrollments.student_id', $studentId->value());
            })
            ->select([
                'course_offerings.*',
                'enrollments.status as enrollment_status',
            ])
            ->get()
            ->toArray();
    }
}
