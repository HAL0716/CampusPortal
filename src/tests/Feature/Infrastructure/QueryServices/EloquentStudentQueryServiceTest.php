<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\Student\DTOs\StudentDTO;
use App\Domain\Student\Enums\StudentStatus;
use App\Infrastructure\QueryServices\EloquentStudentQueryService;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentStudentQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    private function queryService(): EloquentStudentQueryService
    {
        return app(EloquentStudentQueryService::class);
    }

    public function test_find_all_returns_active_students_in_student_number_order(): void
    {
        $activeStudents = [
            ['student_number' => '000001', 'status' => StudentStatus::ACTIVE],
            ['student_number' => '000002', 'status' => StudentStatus::ACTIVE],
        ];

        Student::factory()->createMany($activeStudents);
        Student::factory()->create([
            'student_number' => '000003',
            'status' => StudentStatus::GRADUATED,
        ]);

        $result = $this->queryService()->findAll();

        self::assertCount(count($activeStudents), $result);
        self::assertSame(
            array_column($activeStudents, 'student_number'),
            array_map(
                fn (StudentDTO $student) => $student->studentNumber,
                $result,
            ),
        );
    }

    public function test_find_all_returns_student_information(): void
    {
        $department = Department::factory()->create(['name' => '情報システム学科']);
        $user = User::factory()->create(['name' => '山田太郎']);

        $student = Student::factory()->create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'student_number' => '000001',
            'status' => StudentStatus::ACTIVE,
        ]);

        $result = $this->queryService()->findAll();

        self::assertCount(1, $result);
        self::assertSame($student->student_number, $result[0]->studentNumber);
        self::assertSame($user->name, $result[0]->name);
        self::assertSame($department->name, $result[0]->department);
    }

    public function test_find_all_returns_empty_when_no_active_students_exist(): void
    {
        Student::factory()->create([
            'student_number' => '000001',
            'status' => StudentStatus::GRADUATED,
        ]);

        self::assertSame([], $this->queryService()->findAll());
    }
}
