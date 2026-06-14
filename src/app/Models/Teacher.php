<?php

namespace App\Models;

use App\Enums\TeacherStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'department_id', 'status'])]
class Teacher extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function casts(): array
    {
        return [
            'status' => TeacherStatus::class,
        ];
    }

    public function resign(): void
    {
        $this->update([
            'status' => TeacherStatus::RESIGNED,
        ]);
    }
}
