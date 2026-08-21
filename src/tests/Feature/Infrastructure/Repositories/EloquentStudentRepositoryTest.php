<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\Repositories\EloquentStudentRepository;
use App\Models\Student as StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentStudentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentStudentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EloquentStudentRepository::class);
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

    public function test_can_get_student_by_user_id(): void
    {
        $student = StudentModel::factory()->create();

        $found = $this->repository->getByUserId(new UserId($student->user_id));

        self::assertInstanceOf(Student::class, $found);
        self::assertSame($student->id, $found->id()->value());
        self::assertSame($student->user_id, $found->userId()->value());
    }

    public function test_throws_exception_when_student_not_found_by_user_id(): void
    {
        $this->expectException(StudentNotFoundException::class);

        $this->repository->getByUserId(new UserId(999999));
    }
}
