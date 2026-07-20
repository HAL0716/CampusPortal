<?php

namespace App\Models;

use App\Domain\Course\CourseTerm;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'term'])]
class Course extends Model
{
    protected function casts(): array
    {
        return [
            'term' => CourseTerm::class,
        ];
    }
}
