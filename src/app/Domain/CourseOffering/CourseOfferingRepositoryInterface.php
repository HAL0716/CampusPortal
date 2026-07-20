<?php

namespace App\Domain\CourseOffering;

interface CourseOfferingRepositoryInterface
{
    public function findAll(): array;
}
