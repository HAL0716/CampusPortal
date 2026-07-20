<?php

namespace App\Models;

use App\Domain\Course\CourseTerm;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'description', 'term'])]
class Course extends Model
{
    protected function casts(): array
    {
        return [
            'term' => CourseTerm::class,
        ];
    }

    public function offerings(): HasOne // Semester の追加 -> HasMany に変更
    {
        return $this->hasOne(CourseOffering::class);
    }
}
