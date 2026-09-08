<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\Student\DTOs\StudentDTO;
use App\Application\Contexts\Student\Services\StudentQueryService;
use App\Domain\Student\Enums\StudentStatus;
use App\Models\Student;

final readonly class EloquentStudentQueryService implements StudentQueryService
{
    /** @return array<StudentDTO> */
    public function findAll(): array
    {
        return Student::query()
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('departments', 'departments.id', '=', 'students.department_id')
            ->where('students.status', StudentStatus::ACTIVE)
            ->orderBy('students.student_number')
            ->select([
                'students.id',
                'users.name',
                'students.student_number',
                'departments.name as department',
            ])
            ->get()
            ->map(
                fn (object $student): StudentDTO => new StudentDTO(
                    id: $student->id,
                    name: $student->name,
                    studentNumber: $student->student_number,
                    department: $student->department,
                )
            )
            ->all();
    }
}
