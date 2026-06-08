<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
class Department extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function options(): array
    {
        return self::query()
            ->orderBy('code')
            ->get()
            ->map(fn (self $department) => [
                'value' => $department->id,
                'label' => $department->name,
            ])
            ->values()
            ->all();
    }
}
