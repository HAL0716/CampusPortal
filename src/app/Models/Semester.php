<?php

namespace App\Models;

use App\Domain\Academic\Enums\Term;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['academic_year', 'term', 'start_date', 'end_date'])]
class Semester extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'term' => Term::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function courseOfferings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }
}
