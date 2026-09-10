<?php

namespace App\Application\Contexts\Student\Services;

use App\Application\Contexts\Student\DTOs\StudentDetailDTO;
use App\Application\Contexts\Student\DTOs\StudentDTO;
use App\Domain\Student\ValueObjects\StudentId;

interface StudentQueryService
{
    public function getDetail(StudentId $id): StudentDetailDTO;

    /** @return array<StudentDTO> */
    public function findAll(): array;
}
