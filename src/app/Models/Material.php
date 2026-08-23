<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['course_offering_id', 'title', 'description', 'file_path', 'publish_date'])]
class Material extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'publish_date' => 'datetime',
        ];
    }

    public function scopePublishedAt(Builder $query, CarbonInterface $at): Builder
    {
        return $query->where(
            fn (Builder $query) => $query
                ->where('publish_date', '<=', $at)
                ->orWhereNull('publish_date'),
        );
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }
}
