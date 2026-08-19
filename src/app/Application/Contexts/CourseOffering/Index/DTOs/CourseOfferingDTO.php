<?php

namespace App\Application\Contexts\CourseOffering\Index\DTOs;

use App\Application\Contexts\CourseOffering\Index\Enums\CourseOfferingStatus;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public CourseOfferingStatus $status,
    ) {}
}
