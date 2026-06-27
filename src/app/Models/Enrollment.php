<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['student_id', 'course_offering_id', 'status'])]
class Enrollment extends Model
{
    protected function casts(): array
    {
        return [
            'status' => EnrollmentStatus::class,
        ];
    }

    public function scopeForStudent(Builder $query, StudentProfile $student): Builder
    {
        return $query->where('student_profile_id', $student->id);
    }

    public function scopeCurrentTerm(Builder $query): Builder
    {
        return $query->whereHas('courseOffering', fn (Builder $query) => $query->currentTerm());
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function finalGrade(): HasOne
    {
        return $this->hasOne(FinalGrade::class);
    }
}
