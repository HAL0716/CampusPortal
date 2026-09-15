<?php

namespace App\Domain\Semester\Repositories;

use App\Domain\Semester\Entities\Semester;
use Carbon\CarbonImmutable;

interface SemesterRepository
{
    public function save(Semester $semester): Semester;

    public function getByDate(CarbonImmutable $date): Semester;

    public function getLatest(): Semester;
}
