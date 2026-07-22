<?php

namespace Database\Seeders;

use App\Models\Department;
use Database\Seeders\Data\DepartmentData;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (DepartmentData::all() as $name) {
            Department::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
