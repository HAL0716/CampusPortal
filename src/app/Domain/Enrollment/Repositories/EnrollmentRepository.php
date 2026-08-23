<?php

namespace App\Domain\Enrollment\Repositories;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Student\ValueObjects\StudentId;

interface EnrollmentRepository
{
    public function save(Enrollment $enrollment): Enrollment;

    public function findById(EnrollmentId $enrollmentId): ?Enrollment;

    public function getById(EnrollmentId $enrollmentId): Enrollment;

    public function findByStudentAndCourseOffering(StudentId $studentId, CourseOfferingId $courseOfferingId): ?Enrollment;

    public function getByStudentAndCourseOffering(StudentId $studentId, CourseOfferingId $courseOfferingId): Enrollment;
}
