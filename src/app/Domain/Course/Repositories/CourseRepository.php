<?php

namespace App\Domain\Course\Repositories;

use App\Domain\Academic\Enums\Term;
use App\Domain\Course\Entities\Course;

interface CourseRepository
{
    /** @return array<Course> */
    public function getByTerm(Term $term): array;
}
