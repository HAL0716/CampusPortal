<?php

namespace Database\Seeders;

use App\Domain\Role\RoleType;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'name' => '佐藤学生',
                'email' => 's250001@university.ac.jp',
                'password' => 'pass1234',
            ],
            [
                'name' => '鈴木学生',
                'email' => 's250002@university.ac.jp',
                'password' => 'pass1234',
            ],
            [
                'name' => '高橋学生',
                'email' => 's250003@university.ac.jp',
                'password' => 'pass1234',
            ],
            [
                'name' => '田中学生',
                'email' => 's250004@university.ac.jp',
                'password' => 'pass1234',
            ],
            [
                'name' => '伊藤学生',
                'email' => 's250005@university.ac.jp',
                'password' => 'pass1234',
            ],
        ];

        foreach ($students as $student) {
            $user = User::firstOrCreate([
                'email' => $student['email'],
            ], [
                'name' => $student['name'],
                'password' => Hash::make($student['password']),
            ]);

            $role = Role::where(
                'name',
                RoleType::STUDENT
            )->firstOrFail();

            $user->roles()->syncWithoutDetaching([
                $role->id,
            ]);

            Student::firstOrCreate([
                'user_id' => $user->id,
            ]);
        }
    }
}
