<?php

namespace App\Domain\Semester;

use Carbon\CarbonImmutable;

interface SemesterRepositoryInterface
{
    public function findByDate(CarbonImmutable $date): ?Semester;
}
