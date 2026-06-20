<?php

namespace App\Models;

use App\Enums\Degree;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('degree', 'year')]
class Curriculum extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'degree' => Degree::class,
        ];
    }
}
