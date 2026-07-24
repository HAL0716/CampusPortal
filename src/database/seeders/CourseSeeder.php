<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use Database\Seeders\Data\CourseData;
use Database\Seeders\Data\DepartmentData;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::whereIn('name', DepartmentData::all())
            ->pluck('id', 'name');

        foreach (CourseData::all() as $course) {
            $courseModel = Course::firstOrCreate(
                [
                    'name' => $course['name'],
                ],
                collect($course)->except('departments')->toArray()
            );

            $departmentIds = collect($course['departments'])
                ->map(fn (string $name) => $departments[$name])
                ->all();

            $courseModel->departments()->syncWithoutDetaching($departmentIds);

            $teacherIds = Department::whereKey($departmentIds)
                ->with('teachers:id')
                ->get()
                ->pluck('teachers')
                ->flatten()
                ->pluck('id')
                ->unique()
                ->all();

            $courseModel->teachers()->syncWithoutDetaching($teacherIds);
        }
    }
}
