<?php

namespace App\Domain\Semester;

use App\Domain\Academic\Term;

interface SemesterRepositoryInterface
{
    public function find(int $academicYear, Term $term): ?Semester;
}
