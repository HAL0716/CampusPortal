<?php

namespace App\Application\CourseOffering;

use App\Domain\CourseOffering\CourseOfferingRepositoryInterface;

class ListCourseOfferingUseCase
{
    public function __construct(
        private CourseOfferingRepositoryInterface $courseOfferings
    ) {}

    /**
     * @return CourseOfferingListDTO[]
     */
    public function execute(): array
    {
        return array_map(
            fn (array $courseOffering) => CourseOfferingListDTO::fromArray($courseOffering),
            $this->courseOfferings->findAll()
        );
    }
}
