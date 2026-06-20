<?php

namespace Database\Seeders;

use App\Enums\Degree;
use App\Models\Curriculum;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    private const STUDENT_COUNT_PER_GROUP = 3;

    public function run(): void
    {
        $departments = Department::all();
        $curriculums = Curriculum::all();

        if ($departments->isEmpty() || $curriculums->isEmpty()) {
            return;
        }

        foreach ($curriculums as $curriculum) {
            foreach ($departments as $department) {
                for ($i = 1; $i <= self::STUDENT_COUNT_PER_GROUP; $i++) {
                    $studentNo = $this->generateStudentNo(
                        $curriculum,
                        $department->id,
                        $i
                    );

                    $user = User::firstOrCreate(
                        [
                            'login_id' => 's'.$studentNo,
                        ],
                        User::factory()
                            ->student()
                            ->make([
                                'login_id' => 's'.$studentNo,
                            ])
                            ->getAttributes()
                    );

                    $user->studentProfile()->firstOrCreate([
                        'student_no' => $studentNo,
                        'department_id' => $department->id,
                        'curriculum_id' => $curriculum->id,
                    ]);
                }
            }
        }
    }

    private function generateStudentNo(
        Curriculum $curriculum,
        int $departmentId,
        int $index
    ): string {
        $year = (int) substr(now()->year, -2);

        $yearIndex = match ($curriculum->degree) {
            Degree::BACHELOR => 0 + $curriculum->year,
            Degree::MASTER => 4 + $curriculum->year,
            Degree::DOCTOR => 6 + $curriculum->year,
        };

        $admissionYear = ($year - $yearIndex + 100) % 100;

        return str_pad((string) $admissionYear, 2, '0', STR_PAD_LEFT)
            .$departmentId
            .str_pad((string) $index, 3, '0', STR_PAD_LEFT);
    }
}
