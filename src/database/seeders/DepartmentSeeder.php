<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'code' => 'INFO',
                'name' => '情報工学科',
            ],
            [
                'code' => 'ELEC',
                'name' => '電気工学科',
            ],
            [
                'code' => 'MECH',
                'name' => '機械工学科',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                ['name' => $department['name']]
            );
        }
    }
}
