<?php

namespace App\Application\Contexts\CourseOffering\DTOs;

use App\Application\Contexts\CourseOffering\Enums\CourseOfferingStatus;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public CourseOfferingStatus $status,
    ) {}
}
