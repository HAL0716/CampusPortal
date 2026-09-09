<?php

namespace App\Application\Contexts\Student\Services;

use App\Application\Contexts\Student\DTOs\StudentDTO;

interface StudentQueryService
{
    /** @return array<StudentDTO> */
    public function findAll(): array;
}
