<?php

namespace App\Application\Contexts\CourseOffering\Show\DTOs;

use App\Application\Contexts\CourseOffering\Index\Enums\CourseOfferingStatus;

final readonly class CourseOfferingDTO
{
    /**
     * @param  array<MaterialDTO>  $materials
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public CourseOfferingStatus $status,
        public array $teachers,
        public array $materials,
    ) {}
}
