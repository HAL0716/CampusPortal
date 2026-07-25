<?php

namespace App\Infrastructure\Authorization;

use App\Application\Authorization\EnrollmentAuthorizationServiceInterface;
use App\Domain\CourseOffering\CourseOfferingRepositoryInterface;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Teacher\TeacherRepositoryInterface;
use App\Domain\User\UserId;

final class EnrollmentAuthorizationService implements EnrollmentAuthorizationServiceInterface
{
    public function __construct(
        private readonly TeacherRepositoryInterface $teachers,
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CourseOfferingRepositoryInterface $courseOfferings,
    ) {}

    public function canManage(UserId $userId, EnrollmentId $enrollmentId): bool
    {
        $teacher = $this->teachers->findByUserId($userId);
        if ($teacher === null) {
            return false;
        }

        $enrollment = $this->enrollments->findById($enrollmentId);
        if ($enrollment === null) {
            return false;
        }

        $courseOffering = $this->courseOfferings->findById($enrollment->courseOfferingId());
        if ($courseOffering === null) {
            return false;
        }

        return $courseOffering->hasTeacher($teacher->id());
    }
}
