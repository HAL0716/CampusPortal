<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\Period;
use App\Enums\Term;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['department_id', 'name', 'description', 'credits', 'term', 'default_teacher_id', 'default_day_of_week', 'default_period'])]
class Course extends Model
{
    protected function casts(): array
    {
        return [
            'term' => Term::class,
            'default_day_of_week' => DayOfWeek::class,
            'default_period' => Period::class,
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function defaultTeacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class, 'default_teacher_id');
    }
}
