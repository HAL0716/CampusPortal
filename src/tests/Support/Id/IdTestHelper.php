<?php

namespace Tests\Support\Id;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Semester\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Domain\User\ValueObjects\UserId;

trait IdTestHelper
{
    private function userId(?int $id = null): UserId
    {
        return new UserId($id ?? 1);
    }

    private function studentId(?int $id = null): StudentId
    {
        return new StudentId($id ?? 1);
    }

    private function teacherId(?int $id = null): TeacherId
    {
        return new TeacherId($id ?? 1);
    }

    private function semesterId(?int $id = null): SemesterId
    {
        return new SemesterId($id ?? 1);
    }

    private function courseOfferingId(?int $id = null): CourseOfferingId
    {
        return new CourseOfferingId($id ?? 1);
    }

    private function enrollmentId(?int $id = null): EnrollmentId
    {
        return new EnrollmentId($id ?? 1);
    }
}
