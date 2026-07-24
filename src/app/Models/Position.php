<?php

namespace App\Models;

use App\Domain\Position\PositionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Position extends Model
{
    protected function casts(): array
    {
        return [
            'name' => PositionType::class,
        ];
    }
}
