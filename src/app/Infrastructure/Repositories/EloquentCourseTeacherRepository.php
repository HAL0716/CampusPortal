<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Course\Repositories\CourseTeacherRepository;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use Illuminate\Support\Facades\DB;

final class EloquentCourseTeacherRepository implements CourseTeacherRepository
{
    public function exists(CourseId $courseId, TeacherId $teacherId): bool
    {
        return DB::table('course_teacher')
            ->where('course_id', $courseId->value())
            ->where('teacher_id', $teacherId->value())
            ->exists();
    }
}
