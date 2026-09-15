<?php

namespace App\Application\Contexts\Semester\Services;

use App\Application\Contexts\Semester\DTOs\SemesterDetailDTO;
use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Domain\Semester\ValueObjects\SemesterId;

interface SemesterQueryService
{
    public function getDetail(SemesterId $semester): SemesterDetailDTO;

    /** @return array<SemesterDTO> */
    public function findAll(): array;

    public function getLatest(): SemesterDTO;
}
