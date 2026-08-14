<?php

namespace App\Domain\Semester\Repositories;

use App\Domain\Semester\Entities\Semester;
use Carbon\CarbonImmutable;

interface SemesterRepository
{
    public function findByDate(CarbonImmutable $date): ?Semester;
}
