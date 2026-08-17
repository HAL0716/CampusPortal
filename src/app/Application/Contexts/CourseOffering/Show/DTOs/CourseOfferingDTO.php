<?php

namespace App\Application\Contexts\CourseOffering\Show\DTOs;

final readonly class CourseOfferingDTO
{
    /**
     * @param  array<MaterialDTO>  $materials
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public array $teachers,
        public array $materials,
    ) {}
}
