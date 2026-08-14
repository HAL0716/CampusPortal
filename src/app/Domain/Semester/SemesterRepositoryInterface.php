<?php

namespace App\Domain\Semester;

use App\Domain\Semester\Entities\Semester;
use Carbon\CarbonImmutable;

interface SemesterRepositoryInterface
{
    public function findByDate(CarbonImmutable $date): ?Semester;
}
