<?php

namespace App\Models;

use App\Enums\Term;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['academic_year', 'term', 'registration_start', 'registration_end', 'lecture_start', 'lecture_end'])]
class AcademicTerm extends Model
{
    public function casts(): array
    {
        return [
            'term' => Term::class,
            'registration_start' => 'date',
            'registration_end' => 'date',
            'lecture_start' => 'date',
            'lecture_end' => 'date',
        ];
    }
}
