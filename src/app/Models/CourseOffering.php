<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\Period;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['course_id', 'academic_term_id', 'teacher_id', 'day_of_week', 'period'])]
class CourseOffering extends Model
{
    public function casts(): array
    {
        return [
            'day_of_week' => DayOfWeek::class,
            'period' => Period::class,
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }
}
