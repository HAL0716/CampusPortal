<?php

namespace Database\Seeders;

use App\Domain\Position\Enums\PositionType;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PositionType::cases() as $position) {
            Position::firstOrCreate([
                'name' => $position->value,
            ]);
        }
    }
}
