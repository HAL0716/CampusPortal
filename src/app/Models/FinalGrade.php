<?php

namespace App\Models;

use App\Enums\Grade;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['enrollment_id', 'grade'])]
class FinalGrade extends Model
{
    protected function casts(): array
    {
        return [
            'grade' => Grade::class,
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
