<?php

namespace Database\Seeders;

use App\Domain\Position\Enums\PositionType;
use App\Domain\Role\Enums\RoleType;
use App\Domain\Teacher\Enums\TeacherStatus;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\Data\DepartmentData;
use Database\Seeders\Data\TeacherData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', RoleType::TEACHER)->firstOrFail();

        $positions = Position::whereIn('name', PositionType::values())
            ->pluck('id', 'name');

        $departments = Department::whereIn('name', DepartmentData::all())
            ->pluck('id', 'name');

        foreach (TeacherData::all() as $teacher) {
            $user = User::firstOrCreate(
                [
                    'email' => $teacher['email'],
                ],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make($teacher['password']),
                ]
            );

            $user->roles()->syncWithoutDetaching([$role->id]);

            $teacherModel = Teacher::firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'position_id' => $positions[$teacher['position']->value],
                    'status' => TeacherStatus::ACTIVE,
                ]
            );

            $teacherModel->departments()->syncWithoutDetaching(
                collect($teacher['department'])
                    ->map(fn (string $name) => $departments[$name])
                    ->all()
            );
        }
    }
}
