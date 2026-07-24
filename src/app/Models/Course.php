<?php

namespace App\Models;

use App\Domain\Academic\Term;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'term'])]
class Course extends Model
{
    protected function casts(): array
    {
        return [
            'term' => Term::class,
        ];
    }

    public function offerings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'course_department');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'course_teacher');
    }
}
