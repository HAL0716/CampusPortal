<?php

namespace Database\Seeders;

use App\Enums\Term;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Department;
use App\Models\TeacherProfile;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    private const COURSES_PER_GROUP = 3;

    public function run(): void
    {
        $departments = Department::all();
        $curriculums = Curriculum::all();

        if ($departments->isEmpty() || $curriculums->isEmpty()) {
            return;
        }

        $teachers = TeacherProfile::all()
            ->groupBy('department_id');

        foreach ($departments as $department) {
            $departmentTeachers = $teachers->get($department->id, collect());

            foreach ($curriculums as $curriculum) {
                foreach (Term::cases() as $term) {
                    for ($i = 1; $i <= self::COURSES_PER_GROUP; $i++) {
                        $name = $this->generateCourseName($department, $curriculum, $term, $i);

                        $course = Course::firstOrCreate(
                            [
                                'name' => $name,
                            ],
                            Course::factory()
                                ->make([
                                    'name' => $name,
                                    'department_id' => $department->id,
                                    'term' => $term,
                                    'default_teacher_id' => $departmentTeachers->isNotEmpty()
                                        ? $departmentTeachers->random()->id
                                        : null,
                                ])
                                ->getAttributes()
                        );

                        $course->courseTargets()->firstOrCreate([
                            'curriculum_id' => $curriculum->id,
                        ]);
                    }
                }
            }
        }
    }

    private function generateCourseName(
        Department $department,
        Curriculum $curriculum,
        Term $term,
        int $index
    ): string {
        $depart = mb_substr($department->name, 0, 2, 'UTF-8');
        $degree = strtoupper(substr($curriculum->degree->value, 0, 1));

        return "{$depart}-{$degree}{$curriculum->year}-{$term->value}-{$index}";
    }
}
