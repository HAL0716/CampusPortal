<?php

namespace App\Models;

use App\Domain\Position\PositionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Position extends Model
{
    protected function casts(): array
    {
        return [
            'name' => PositionType::class,
        ];
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
