<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\Student\DTOs\StudentDetailDTO;
use App\Application\Contexts\Student\DTOs\StudentDTO;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\ValueObjects\StudentId;
use App\Infrastructure\QueryServices\EloquentStudentQueryService;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\IdTestHelper;
use Tests\TestCase;

final class EloquentStudentQueryServiceTest extends TestCase
{
    use IdTestHelper;
    use RefreshDatabase;

    private EloquentStudentQueryService $queryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryService = new EloquentStudentQueryService;
    }

    public function test_returns_student_detail(): void
    {
        $student = Student::factory()->create(['status' => StudentStatus::ACTIVE]);
        $semester = Semester::factory()->create();
        $statuses = [EnrollmentStatus::COMPLETED, EnrollmentStatus::COMPLETED, EnrollmentStatus::ENROLLED];

        $courseOfferings = CourseOffering::factory()
            ->count(count($statuses))
            ->for($semester)
            ->create();

        foreach ($statuses as $index => $status) {
            Enrollment::factory()
                ->for($student)
                ->for($courseOfferings[$index])
                ->create(['status' => $status]);
        }

        $expected = new StudentDetailDTO(
            id: $student->id,
            name: $student->user->name,
            studentNumber: $student->student_number,
            department: $student->department->name,
            credits: count(
                array_filter($statuses,
                    fn (EnrollmentStatus $status) => $status === EnrollmentStatus::COMPLETED
                )
            ),
            status: $student->status->label(),
            transitions: [
                [
                    'value' => StudentStatus::SUSPENDED->value,
                    'label' => StudentStatus::SUSPENDED->label(),
                ],
                [
                    'value' => StudentStatus::EXPELLED->value,
                    'label' => StudentStatus::EXPELLED->label(),
                ],
                [
                    'value' => StudentStatus::GRADUATED->value,
                    'label' => StudentStatus::GRADUATED->label(),
                ],
            ],
        );

        $result = $this->queryService->getDetail(new StudentId($student->id));

        self::assertEquals($expected, $result);
    }

    public function test_throws_exception_when_student_does_not_exist(): void
    {
        $this->expectException(StudentNotFoundException::class);

        $this->queryService->getDetail($this->studentId());
    }

    public function test_find_all_returns_active_students_in_student_number_order(): void
    {
        $students = [
            ['student_number' => '000002', 'status' => StudentStatus::ACTIVE],
            ['student_number' => '000001', 'status' => StudentStatus::ACTIVE],
            ['student_number' => '000003', 'status' => StudentStatus::GRADUATED],
        ];

        Student::factory()->createMany($students);

        $expected = collect($students)
            ->filter(fn (array $student) => $student['status'] === StudentStatus::ACTIVE)
            ->sortBy('student_number')
            ->pluck('student_number')
            ->all();

        $result = $this->queryService->findAll();

        self::assertSame($expected, array_map(fn (StudentDTO $student) => $student->studentNumber, $result));
    }

    public function test_find_all_returns_student_information(): void
    {
        $student = Student::factory()->create();

        $expected = new StudentDTO(
            id: $student->id,
            name: $student->user->name,
            studentNumber: $student->student_number,
            department: $student->department->name,
        );

        $result = $this->queryService->findAll();

        self::assertCount(1, $result);
        self::assertEquals($expected, $result[0]);
    }

    public function test_find_all_returns_empty_when_no_active_students_exist(): void
    {
        Student::factory()->create(['status' => StudentStatus::GRADUATED]);

        $expected = [];

        $result = $this->queryService->findAll();

        self::assertSame($expected, $result);
    }
}
