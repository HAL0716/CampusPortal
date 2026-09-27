<?php

namespace App\Domain\Course\Repositories;

use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Teacher\ValueObjects\TeacherId;

interface CourseTeacherRepository
{
    public function exists(CourseId $courseId, TeacherId $teacherId): bool;
}
