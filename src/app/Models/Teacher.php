<?php

namespace App\Models;

use App\Domain\Teacher\TeacherStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['user_id', 'position_id', 'status'])]
class Teacher extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => TeacherStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_teacher');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_teacher');
    }
}
