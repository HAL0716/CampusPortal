<?php

namespace Database\Seeders;

use App\Domain\Role\RoleType;
use App\Domain\Student\Enums\StudentStatus;
use App\Models\Department;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\Data\DepartmentData;
use Database\Seeders\Data\StudentData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', RoleType::STUDENT)->firstOrFail();

        $departments = Department::whereIn('name', DepartmentData::all())
            ->pluck('id', 'name');

        foreach (StudentData::all() as $student) {
            $user = User::firstOrCreate(
                [
                    'email' => $student['email'],
                ],
                [
                    'name' => $student['name'],
                    'password' => Hash::make($student['password']),
                ]
            );

            $user->roles()->syncWithoutDetaching([$role->id]);

            Student::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'student_number' => $student['student_number'],
                ],
                [
                    'department_id' => $departments[$student['department']],
                    'status' => StudentStatus::ACTIVE,
                ]
            );
        }
    }
}
