<?php

namespace App\Models;

use App\Enums\Term;
use App\Support\SystemClock;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public static function current(): ?self
    {
        $now = SystemClock::now();

        return self::whereDate('lecture_start', '<=', $now)
            ->whereDate('lecture_end', '>=', $now)
            ->first();
    }

    public function courseOfferings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }
}
