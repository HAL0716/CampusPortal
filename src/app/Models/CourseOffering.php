<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\Period;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function scopeCurrentTerm(Builder $query): Builder
    {
        $term = AcademicTerm::current();

        if (! $term) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('academic_term_id', $term->id);
    }
}
