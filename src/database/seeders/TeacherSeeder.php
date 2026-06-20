<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    private const TEACHERS = [
        ['name' => '佐藤教授', 'login_id' => 'sato'],
        ['name' => '鈴木教授', 'login_id' => 'suzuki'],
        ['name' => '高橋教授', 'login_id' => 'takahashi'],
        ['name' => '田中教授', 'login_id' => 'tanaka'],
        ['name' => '伊藤教授', 'login_id' => 'ito'],
        ['name' => '渡辺教授', 'login_id' => 'watanabe'],
        ['name' => '山本教授', 'login_id' => 'yamamoto'],
        ['name' => '中村教授', 'login_id' => 'nakamura'],
        ['name' => '小林教授', 'login_id' => 'kobayashi'],
        ['name' => '加藤教授', 'login_id' => 'kato'],
    ];

    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            return;
        }

        foreach (self::TEACHERS as $index => $teacher) {
            $user = User::firstOrCreate(
                [
                    'login_id' => $teacher['login_id'],
                ],
                User::factory()
                    ->teacher()
                    ->make($teacher)
                    ->getAttributes()
            );

            $user->teacherProfile()->firstOrCreate([
                'department_id' => $departments[$index % $departments->count()]->id,
            ]);
        }
    }
}
