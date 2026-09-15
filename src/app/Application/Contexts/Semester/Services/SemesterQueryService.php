<?php

namespace App\Application\Contexts\Semester\Services;

use App\Application\Contexts\Semester\DTOs\SemesterDTO;

interface SemesterQueryService
{
    /** @return array<SemesterDTO> */
    public function findAll(): array;
}
