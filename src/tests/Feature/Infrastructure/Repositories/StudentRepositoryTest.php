<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Student\Student;
use App\Domain\User\UserId;
use App\Infrastructure\Repositories\StudentRepository;
use App\Models\Student as StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class StudentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StudentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(StudentRepository::class);
    }

    public function test_can_find_student_by_user_id(): void
    {
        $student = StudentModel::factory()->create();

        $found = $this->repository->findByUserId(new UserId($student->user_id));

        self::assertInstanceOf(Student::class, $found);
        self::assertSame($student->id, $found->id()->value());
        self::assertSame($student->user_id, $found->userId()->value());
    }

    public function test_returns_null_when_student_not_found_by_user_id(): void
    {
        $found = $this->repository->findByUserId(new UserId(999));

        self::assertNull($found);
    }
}
