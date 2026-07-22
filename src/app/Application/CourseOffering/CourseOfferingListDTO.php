<?php

namespace App\Application\CourseOffering;

use App\Domain\Enrollment\EnrollmentStatus;

class CourseOfferingListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?EnrollmentStatus $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['course']['name'],
            status: isset($data['enrollment_status'])
                ? EnrollmentStatus::from($data['enrollment_status'])
                : null,
        );
    }
}
