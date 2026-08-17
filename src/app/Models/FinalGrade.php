<?php

namespace App\Models;

use App\Domain\FinalGrade\Enums\FinalGradeType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['enrollment_id', 'grade'])]
class FinalGrade extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'grade' => FinalGradeType::class,
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
