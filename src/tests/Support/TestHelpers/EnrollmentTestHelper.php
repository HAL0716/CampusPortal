<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Enums\EnrollmentStatus;

trait EnrollmentTestHelper
{
    use IdTestHelper;

    protected function createEnrollment(
        ?int $studentId = null,
        ?int $courseOfferingId = null,
    ): Enrollment {
        return Enrollment::create(
            studentId: $this->studentId($studentId),
            courseOfferingId: $this->courseOfferingId($courseOfferingId),
        );
    }

    protected function reconstructEnrollment(
        ?int $id = null,
        ?int $studentId = null,
        ?int $courseOfferingId = null,
        ?EnrollmentStatus $status = null,
    ): Enrollment {
        return Enrollment::reconstruct(
            id: $this->enrollmentId($id),
            studentId: $this->studentId($studentId),
            courseOfferingId: $this->courseOfferingId($courseOfferingId),
            status: $status ?? EnrollmentStatus::ENROLLED,
        );
    }
}
