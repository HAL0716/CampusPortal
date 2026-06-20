<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    private const DEPARTMENTS = [
        ['name' => '機械工学科'],
        ['name' => '電気工学科'],
        ['name' => '情報工学科'],
    ];

    public function run(): void
    {
        foreach (self::DEPARTMENTS as $department) {
            Department::firstOrCreate($department);
        }
    }
}
